<?php
/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare(strict_types=1);

namespace WPRuby_UPS_Libs\DVDoug\BoxPacker;

use function array_filter;
use function count;
use WPRuby_UPS_Libs\Psr\Log\LoggerAwareInterface;
use WPRuby_UPS_Libs\Psr\Log\LoggerAwareTrait;
use WPRuby_UPS_Libs\Psr\Log\NullLogger;
use function usort;

/**
 * Figure out orientations for an item and a given set of dimensions.
 *
 * @author Doug Wright
 * @internal
 */
class OrientatedItemFactory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var Box */
    protected $box;

    /**
     * Whether the packer is in single-pass mode.
     *
     * @var bool
     */
    protected $singlePassMode = false;

    /**
     * @var OrientatedItem[]
     */
    protected static $emptyBoxCache = [];

    public function __construct(Box $box)
    {
        $this->box = $box;
        $this->logger = new NullLogger();
    }

    public function setSinglePassMode(bool $singlePassMode): void
    {
        $this->singlePassMode = $singlePassMode;
    }

    /**
     * Get the best orientation for an item.
     */
    public function getBestOrientation(
        Item $item,
        ?OrientatedItem $prevItem,
        ItemList $nextItems,
        int $widthLeft,
        int $lengthLeft,
        int $depthLeft,
        int $rowLength,
        int $x,
        int $y,
        int $z,
        PackedItemList $prevPackedItemList
    ): ?OrientatedItem {
        $this->logger->debug(
            "evaluating item {$item->getDescription()} for fit",
            [
                'item' => $item,
                'space' => [
                    'widthLeft' => $widthLeft,
                    'lengthLeft' => $lengthLeft,
                    'depthLeft' => $depthLeft,
                ],
            ]
        );

        $possibleOrientations = $this->getPossibleOrientations($item, $prevItem, $widthLeft, $lengthLeft, $depthLeft, $x, $y, $z, $prevPackedItemList);
        $usableOrientations = $this->getUsableOrientations($item, $possibleOrientations);

        if (empty($usableOrientations)) {
            return null;
        }

        $sorter = new OrientatedItemSorter($this, $this->singlePassMode, $widthLeft, $lengthLeft, $depthLeft, $nextItems, $rowLength, $x, $y, $z, $prevPackedItemList);
        $sorter->setLogger($this->logger);
        usort($usableOrientations, $sorter);

        $this->logger->debug('Selected best fit orientation', ['orientation' => $usableOrientations[0]]);

        return $usableOrientations[0];
    }

    /**
     * Find all possible orientations for an item.
     *
     * @return OrientatedItem[]
     */
    public function getPossibleOrientations(
        Item $item,
        ?OrientatedItem $prevItem,
        int $widthLeft,
        int $lengthLeft,
        int $depthLeft,
        int $x,
        int $y,
        int $z,
        PackedItemList $prevPackedItemList
    ): array {
        $permutations = $this->generatePermutations($item, $prevItem);

        //remove any that simply don't fit
        $orientations = [];
        foreach ($permutations as $dimensions) {
            if ($dimensions[0] <= $widthLeft && $dimensions[1] <= $lengthLeft && $dimensions[2] <= $depthLeft) {
                $orientations[] = new OrientatedItem($item, $dimensions[0], $dimensions[1], $dimensions[2]);
            }
        }

        if ($item instanceof ConstrainedPlacementItem && !$this->box instanceof WorkingVolume) {
            $orientations = array_filter($orientations, function (OrientatedItem $i) use ($x, $y, $z, $prevPackedItemList) {
                return $i->getItem()->canBePacked($this->box, $prevPackedItemList, $x, $y, $z, $i->getWidth(), $i->getLength(), $i->getDepth());
            });
        }

        return $orientations;
    }

    /**
     * @return OrientatedItem[]
     */
    public function getPossibleOrientationsInEmptyBox(Item $item): array
    {
        $cacheKey = $item->getWidth() .
            '|' .
            $item->getLength() .
            '|' .
            $item->getDepth() .
            '|' .
            ($item->getKeepFlat() ? '2D' : '3D') .
            '|' .
            $this->box->getInnerWidth() .
            '|' .
            $this->box->getInnerLength() .
            '|' .
            $this->box->getInnerDepth();

        if (isset(static::$emptyBoxCache[$cacheKey])) {
            $orientations = static::$emptyBoxCache[$cacheKey];
        } else {
            $orientations = $this->getPossibleOrientations(
                $item,
                null,
                $this->box->getInnerWidth(),
                $this->box->getInnerLength(),
                $this->box->getInnerDepth(),
                0,
                0,
                0,
                new PackedItemList()
            );
            static::$emptyBoxCache[$cacheKey] = $orientations;
        }

        return $orientations;
    }

    /**
     * @param  OrientatedItem[] $possibleOrientations
     * @return OrientatedItem[]
     */
    protected function getUsableOrientations(
        Item $item,
        array $possibleOrientations
    ): array {
        $orientationsToUse = $stableOrientations = $unstableOrientations = [];

        // Divide possible orientations into stable (low centre of gravity) and unstable (high centre of gravity)
        foreach ($possibleOrientations as $orientation) {
            if ($orientation->isStable() || $this->box->getInnerDepth() === $orientation->getDepth()) {
                $stableOrientations[] = $orientation;
            } else {
                $unstableOrientations[] = $orientation;
            }
        }

        /*
         * We prefer to use stable orientations only, but allow unstable ones if
         * the item doesn't fit in the box any other way
         */
        if (count($stableOrientations) > 0) {
            $orientationsToUse = $stableOrientations;
        } elseif (count($unstableOrientations) > 0) {
            $stableOrientationsInEmptyBox = $this->getStableOrientationsInEmptyBox($item);

            if (count($stableOrientationsInEmptyBox) === 0) {
                $orientationsToUse = $unstableOrientations;
            }
        }

        return $orientationsToUse;
    }

    /**
     * Return the orientations for this item if it were to be placed into the box with nothing else.
     */
    protected function getStableOrientationsInEmptyBox(Item $item): array
    {
        $orientationsInEmptyBox = $this->getPossibleOrientationsInEmptyBox($item);

        return array_filter(
            $orientationsInEmptyBox,
            function (OrientatedItem $orientation) {
                return $orientation->isStable();
            }
        );
    }

    private function generatePermutations(Item $item, ?OrientatedItem $prevItem): array
    {
        $permutations = [];

        //Special case items that are the same as what we just packed - keep orientation
        if ($prevItem && $prevItem->isSameDimensions($item)) {
            $permutations[] = [$prevItem->getWidth(), $prevItem->getLength(), $prevItem->getDepth()];
        } else {
            //simple 2D rotation
            $permutations[] = [$item->getWidth(), $item->getLength(), $item->getDepth()];
            $permutations[] = [$item->getLength(), $item->getWidth(), $item->getDepth()];

            //add 3D rotation if we're allowed
            if (!$item->getKeepFlat()) {
                $permutations[] = [$item->getWidth(), $item->getDepth(), $item->getLength()];
                $permutations[] = [$item->getLength(), $item->getDepth(), $item->getWidth()];
                $permutations[] = [$item->getDepth(), $item->getWidth(), $item->getLength()];
                $permutations[] = [$item->getDepth(), $item->getLength(), $item->getWidth()];
            }
        }

        return $permutations;
    }
}
