<?php
/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare(strict_types=1);

namespace WPRuby_UPS_Libs\DVDoug\BoxPacker\Test;

use function array_filter;
use function count;
use WPRuby_UPS_Libs\DVDoug\BoxPacker\Box;
use WPRuby_UPS_Libs\DVDoug\BoxPacker\ConstrainedItem;
use WPRuby_UPS_Libs\DVDoug\BoxPacker\PackedItem;
use WPRuby_UPS_Libs\DVDoug\BoxPacker\PackedItemList;
use function iterator_to_array;

class ConstrainedTestItem extends TestItem implements ConstrainedItem
{
    /**
     * @var int
     */
    public static $limit = 3;

    public function canBePackedInBox(PackedItemList $alreadyPackedItems, Box $box): bool
    {
        $alreadyPackedType = array_filter(
            iterator_to_array($alreadyPackedItems, false),
            function (PackedItem $item) {
                return $item->getItem()->getDescription() === $this->getDescription();
            }
        );

        return count($alreadyPackedType) + 1 <= static::$limit;
    }
}
