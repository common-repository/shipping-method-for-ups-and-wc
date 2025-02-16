<?php
/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare(strict_types=1);

namespace DVDoug\BoxPacker;

use DVDoug\BoxPacker\Test\TestBox;
use DVDoug\BoxPacker\Test\TestItem;
use function iterator_to_array;
use PHPUnit\Framework\TestCase;

class InfalliblePackerTest extends TestCase
{
    /**
     * From issue #182.
     * @group efficiency
     */
    public function testIssue182B(): void
    {
        $packer = new InfalliblePacker();

        $packer->addBox(new TestBox('1', 225, 283, 165, 249, 206, 259, 151, 15876));
        $packer->addBox(new TestBox('2', 320, 368, 251, 363, 295, 339, 231, 15876));
        $packer->addBox(new TestBox('3', 206, 460, 105, 227, 189, 425, 95, 15876));
        $packer->addBox(new TestBox('4', 470, 473, 327, 658, 434, 437, 301, 15876));
        $packer->addBox(new TestBox('5', 333, 613, 156, 476, 307, 567, 141, 15876));
        $packer->addBox(new TestBox('6', 333, 613, 308, 567, 307, 567, 284, 15876));
        $packer->addBox(new TestBox('7', 473, 692, 378, 1089, 437, 641, 349, 15876));

        $packer->addItem(new TestItem('1', 191, 381, 203, 4536, false));
        $packer->addItem(new TestItem('2', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('3', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('4', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('5', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('6', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('7', 457, 610, 381, 8165, false));
        $packer->addItem(new TestItem('8', 191, 381, 203, 4536, false));
        $packer->addItem(new TestItem('9', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('10', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('11', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('12', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('13', 191, 203, 368, 3992, false));
        $packer->addItem(new TestItem('14', 457, 610, 381, 8165, false));
        $packer->addItem(new TestItem('15', 368, 419, 533, 12909, false));
        $packer->addItem(new TestItem('16', 368, 419, 533, 12909, false));
        $packer->addItem(new TestItem('17', 368, 419, 533, 12909, false));
        $packer->addItem(new TestItem('18', 368, 419, 533, 12909, false));
        $packer->addItem(new TestItem('19', 419, 457, 483, 14751, false));
        $packer->addItem(new TestItem('20', 419, 457, 483, 14751, false));
        $packer->addItem(new TestItem('21', 432, 572, 178, 6749, false));
        $packer->addItem(new TestItem('22', 432, 572, 178, 6749, false));
        $packer->addItem(new TestItem('23', 419, 559, 165, 9770, false));
        $packer->addItem(new TestItem('24', 419, 559, 165, 9770, false));
        $packer->addItem(new TestItem('25', 361, 361, 165, 5330, false));
        $packer->addItem(new TestItem('26', 361, 361, 165, 5330, false));
        $packer->addItem(new TestItem('27', 381, 483, 152, 3738, false));
        $packer->addItem(new TestItem('28', 152, 305, 51, 726, false));
        $packer->addItem(new TestItem('29', 318, 406, 102, 2631, false));
        $packer->addItem(new TestItem('30', 254, 279, 102, 1479, false));
        $packer->addItem(new TestItem('31', 254, 279, 102, 1479, false));
        $packer->addItem(new TestItem('32', 133, 248, 76, 526, false));
        $packer->addItem(new TestItem('33', 133, 248, 76, 526, false));
        $packer->addItem(new TestItem('34', 133, 248, 76, 526, false));
        $packer->addItem(new TestItem('35', 133, 248, 76, 526, false));
        $packer->addItem(new TestItem('36', 173, 305, 91, 1451, false));
        $packer->addItem(new TestItem('37', 203, 381, 140, 2087, false));
        $packer->addItem(new TestItem('38', 191, 318, 140, 1225, false));
        $packer->addItem(new TestItem('39', 140, 356, 76, 962, false));
        $packer->addItem(new TestItem('40', 140, 356, 76, 962, false));
        $packer->addItem(new TestItem('41', 137, 356, 69, 816, false));
        $packer->addItem(new TestItem('42', 137, 356, 69, 816, false));
        $packer->addItem(new TestItem('43', 137, 356, 69, 816, false));
        $packer->addItem(new TestItem('44', 381, 467, 89, 3266, false));
        $packer->addItem(new TestItem('45', 241, 305, 66, 1089, false));
        $packer->addItem(new TestItem('46', 178, 335, 119, 1179, false));
        $packer->addItem(new TestItem('47', 178, 335, 119, 1179, false));
        $packer->addItem(new TestItem('48', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('49', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('50', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('51', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('52', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('53', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('54', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('55', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('56', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('57', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('58', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('59', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('60', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('61', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('62', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('63', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('64', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('65', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('66', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('67', 229, 254, 127, 839, false));
        $packer->addItem(new TestItem('68', 254, 305, 102, 1733, false));
        $packer->addItem(new TestItem('69', 254, 305, 102, 1733, false));
        $packer->addItem(new TestItem('70', 254, 305, 102, 1733, false));
        $packer->addItem(new TestItem('71', 254, 305, 102, 1733, false));
        $packer->addItem(new TestItem('72', 254, 305, 102, 1733, false));
        $packer->addItem(new TestItem('73', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('74', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('75', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('76', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('77', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('78', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('79', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('80', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('81', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('82', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('83', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('84', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('85', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('86', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('87', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('88', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('89', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('90', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('91', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('92', 184, 375, 108, 1461, false));
        $packer->addItem(new TestItem('93', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('94', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('95', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('96', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('97', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('98', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('99', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('100', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('101', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('102', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('103', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('104', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('105', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('106', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('107', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('108', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('109', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('110', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('111', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('112', 152, 229, 57, 871, false));
        $packer->addItem(new TestItem('113', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('114', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('115', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('116', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('117', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('118', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('119', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('120', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('121', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('122', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('123', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('124', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('125', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('126', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('127', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('128', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('129', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('130', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('131', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('132', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('133', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('134', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('135', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('136', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('137', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('138', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('139', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('140', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('141', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('142', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('143', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('144', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('145', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('146', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('147', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('148', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('149', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('150', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('151', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('152', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('153', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('154', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('155', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('156', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('157', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('158', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('159', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('160', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('161', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('162', 178, 178, 25, 363, false));
        $packer->addItem(new TestItem('163', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('164', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('165', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('166', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('167', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('168', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('169', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('170', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('171', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('172', 140, 241, 25, 272, false));
        $packer->addItem(new TestItem('173', 457, 457, 178, 6123, false));
        $packer->addItem(new TestItem('174', 457, 457, 178, 6123, false));
        $packer->addItem(new TestItem('175', 457, 457, 178, 6123, false));
        $packer->addItem(new TestItem('176', 267, 305, 76, 2921, false));
        $packer->addItem(new TestItem('177', 267, 305, 76, 2921, false));
        $packer->addItem(new TestItem('178', 267, 305, 76, 2921, false));
        $packer->addItem(new TestItem('179', 267, 305, 76, 2921, false));
        $packer->addItem(new TestItem('180', 267, 305, 76, 2921, false));
        $packer->addItem(new TestItem('181', 267, 305, 76, 2921, false));
        $packer->addItem(new TestItem('182', 267, 305, 76, 2921, false));
        $packer->addItem(new TestItem('183', 267, 305, 76, 2921, false));
        $packer->addItem(new TestItem('184', 178, 540, 89, 1960, false));
        $packer->addItem(new TestItem('185', 178, 540, 89, 1960, false));
        $packer->addItem(new TestItem('186', 178, 540, 89, 1960, false));
        $packer->addItem(new TestItem('187', 178, 540, 89, 1960, false));
        $packer->addItem(new TestItem('188', 178, 279, 76, 299, false));
        $packer->addItem(new TestItem('189', 178, 279, 76, 299, false));
        $packer->addItem(new TestItem('190', 178, 279, 76, 299, false));
        $packer->addItem(new TestItem('191', 178, 279, 76, 299, false));
        $packer->addItem(new TestItem('192', 178, 279, 76, 299, false));
        $packer->addItem(new TestItem('193', 178, 279, 76, 299, false));
        $packer->addItem(new TestItem('194', 178, 279, 76, 299, false));
        $packer->addItem(new TestItem('195', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('196', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('197', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('198', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('199', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('200', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('201', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('202', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('203', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('204', 203, 203, 25, 417, false));
        $packer->addItem(new TestItem('205', 108, 159, 76, 635, false));
        $packer->addItem(new TestItem('206', 108, 159, 76, 635, false));
        $packer->addItem(new TestItem('207', 108, 159, 76, 635, false));
        $packer->addItem(new TestItem('208', 108, 159, 76, 635, false));
        $packer->addItem(new TestItem('209', 108, 159, 76, 635, false));
        $packer->addItem(new TestItem('210', 108, 159, 76, 635, false));
        $packer->addItem(new TestItem('211', 108, 159, 76, 635, false));
        $packer->addItem(new TestItem('212', 127, 279, 70, 1188, false));
        $packer->addItem(new TestItem('213', 508, 1143, 127, 18144, false));
        $packer->addItem(new TestItem('214', 508, 1143, 127, 18144, false));
        $packer->addItem(new TestItem('215', 508, 1143, 127, 18144, false));
        $packer->addItem(new TestItem('216', 508, 1143, 127, 18144, false));
        $packer->addItem(new TestItem('217', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('218', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('219', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('220', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('221', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('222', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('223', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('224', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('225', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('226', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('227', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('228', 112, 831, 109, 2177, false));
        $packer->addItem(new TestItem('229', 305, 737, 394, 16012, false));
        $packer->addItem(new TestItem('230', 305, 737, 394, 16012, false));
        $packer->addItem(new TestItem('231', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('232', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('233', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('234', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('235', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('236', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('237', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('238', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('239', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('240', 188, 231, 84, 544, false));
        $packer->addItem(new TestItem('241', 531, 1049, 112, 22680, false));
        $packer->addItem(new TestItem('242', 531, 1049, 112, 22680, false));
        $packer->addItem(new TestItem('243', 531, 1049, 112, 22680, false));
        $packer->addItem(new TestItem('244', 531, 1049, 112, 22680, false));
        $packer->addItem(new TestItem('245', 211, 206, 221, 1270, false));
        $packer->addItem(new TestItem('246', 211, 206, 221, 1270, false));
        $packer->addItem(new TestItem('247', 211, 206, 221, 1270, false));
        $packer->addItem(new TestItem('248', 211, 206, 221, 1270, false));
        $packer->addItem(new TestItem('249', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('250', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('251', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('252', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('253', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('254', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('255', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('256', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('257', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('258', 241, 559, 89, 3257, false));
        $packer->addItem(new TestItem('259', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('260', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('261', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('262', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('263', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('264', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('265', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('266', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('267', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('268', 191, 279, 61, 1361, false));
        $packer->addItem(new TestItem('269', 660, 1022, 330, 29102, false));
        $packer->addItem(new TestItem('270', 413, 413, 681, 15876, false));
        $packer->addItem(new TestItem('271', 413, 413, 681, 15876, false));
        $packer->addItem(new TestItem('272', 413, 413, 681, 15876, false));
        $packer->addItem(new TestItem('273', 413, 413, 681, 15876, false));
        $packer->addItem(new TestItem('274', 508, 508, 737, 13608, false));
        $packer->addItem(new TestItem('275', 508, 508, 737, 13608, false));
        $packer->addItem(new TestItem('276', 578, 635, 787, 40007, false));
        $packer->addItem(new TestItem('277', 578, 635, 787, 40007, false));
        $packer->addItem(new TestItem('278', 203, 203, 25, 753, false));
        $packer->addItem(new TestItem('279', 203, 203, 25, 753, false));
        $packer->addItem(new TestItem('280', 203, 203, 25, 753, false));
        $packer->addItem(new TestItem('281', 203, 203, 25, 753, false));
        $packer->addItem(new TestItem('282', 203, 203, 25, 481, false));
        $packer->addItem(new TestItem('283', 203, 203, 25, 481, false));
        $packer->addItem(new TestItem('284', 203, 203, 25, 481, false));
        $packer->addItem(new TestItem('285', 203, 203, 25, 481, false));
        $packer->addItem(new TestItem('286', 203, 203, 25, 481, false));
        $packer->addItem(new TestItem('287', 203, 203, 25, 481, false));
        $packer->addItem(new TestItem('288', 124, 254, 86, 635, false));
        $packer->addItem(new TestItem('289', 124, 254, 86, 635, false));
        $packer->addItem(new TestItem('290', 124, 254, 86, 635, false));
        $packer->addItem(new TestItem('291', 124, 254, 86, 635, false));
        $packer->addItem(new TestItem('292', 124, 254, 86, 635, false));
        $packer->addItem(new TestItem('293', 124, 254, 86, 635, false));
        $packer->addItem(new TestItem('294', 124, 254, 86, 635, false));
        $packer->addItem(new TestItem('295', 124, 254, 86, 635, false));
        $packer->addItem(new TestItem('296', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('297', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('298', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('299', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('300', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('301', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('302', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('303', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('304', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('305', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('306', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('307', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('308', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('309', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('310', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('311', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('312', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('313', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('314', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('315', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('316', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('317', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('318', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('319', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('320', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('321', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('322', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('323', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('324', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('325', 146, 229, 13, 227, false));
        $packer->addItem(new TestItem('326', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('327', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('328', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('329', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('330', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('331', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('332', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('333', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('334', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('335', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('336', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('337', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('338', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('339', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('340', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('341', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('342', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('343', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('344', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('345', 140, 267, 38, 372, false));
        $packer->addItem(new TestItem('346', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('347', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('348', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('349', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('350', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('351', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('352', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('353', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('354', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('355', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('356', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('357', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('358', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('359', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('360', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('361', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('362', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('363', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('364', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('365', 89, 909, 89, 3447, false));
        $packer->addItem(new TestItem('366', 107, 163, 81, 635, false));
        $packer->addItem(new TestItem('367', 107, 163, 81, 635, false));
        $packer->addItem(new TestItem('368', 107, 163, 81, 635, false));
        $packer->addItem(new TestItem('369', 107, 163, 81, 635, false));
        $packer->addItem(new TestItem('370', 107, 163, 81, 635, false));
        $packer->addItem(new TestItem('371', 241, 559, 89, 3284, false));
        $packer->addItem(new TestItem('372', 318, 445, 114, 3475, false));
        $packer->addItem(new TestItem('373', 330, 292, 292, 6350, false));
        $packer->addItem(new TestItem('374', 330, 292, 292, 6350, false));
        $packer->addItem(new TestItem('375', 330, 292, 292, 6350, false));
        $packer->addItem(new TestItem('376', 330, 292, 292, 6350, false));
        $packer->addItem(new TestItem('377', 324, 445, 318, 3819, false));
        $packer->addItem(new TestItem('378', 324, 445, 318, 3819, false));
        $packer->addItem(new TestItem('379', 324, 445, 318, 3819, false));
        $packer->addItem(new TestItem('380', 324, 445, 318, 3819, false));
        $packer->addItem(new TestItem('381', 324, 445, 318, 3819, false));
        $packer->addItem(new TestItem('382', 324, 445, 318, 3819, false));
        $packer->addItem(new TestItem('383', 229, 572, 127, 3411, false));
        $packer->addItem(new TestItem('384', 279, 330, 51, 1157, false));
        $packer->addItem(new TestItem('385', 203, 203, 25, 159, false));
        $packer->addItem(new TestItem('386', 203, 203, 25, 159, false));
        $packer->addItem(new TestItem('387', 203, 203, 25, 159, false));
        $packer->addItem(new TestItem('388', 203, 203, 25, 159, false));
        $packer->addItem(new TestItem('389', 203, 203, 25, 159, false));
        $packer->addItem(new TestItem('390', 203, 203, 25, 159, false));
        $packer->addItem(new TestItem('391', 203, 203, 25, 159, false));
        $packer->addItem(new TestItem('392', 203, 203, 25, 159, false));
        $packer->addItem(new TestItem('393', 203, 203, 51, 354, false));
        $packer->addItem(new TestItem('394', 178, 178, 25, 91, false));
        $packer->addItem(new TestItem('395', 178, 178, 25, 91, false));
        $packer->addItem(new TestItem('396', 178, 178, 25, 91, false));
        $packer->addItem(new TestItem('397', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('398', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('399', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('400', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('401', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('402', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('403', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('404', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('405', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('406', 95, 127, 25, 73, false));
        $packer->addItem(new TestItem('407', 64, 165, 122, 1134, false));
        $packer->addItem(new TestItem('408', 64, 165, 122, 1134, false));
        $packer->addItem(new TestItem('409', 64, 165, 122, 1134, false));
        $packer->addItem(new TestItem('410', 64, 165, 122, 1134, false));
        $packer->addItem(new TestItem('411', 86, 109, 51, 200, false));
        $packer->addItem(new TestItem('412', 86, 109, 51, 200, false));
        $packer->addItem(new TestItem('413', 86, 109, 51, 200, false));
        $packer->addItem(new TestItem('414', 86, 109, 51, 200, false));
        $packer->addItem(new TestItem('415', 86, 109, 51, 200, false));
        $packer->addItem(new TestItem('416', 86, 109, 51, 200, false));
        $packer->addItem(new TestItem('417', 305, 521, 108, 2976, false));
        $packer->addItem(new TestItem('418', 305, 521, 108, 2976, false));

        /** @var PackedBox[] $packedBoxes */
        $packedBoxes = iterator_to_array($packer->pack(), false);

        self::assertCount(44, $packedBoxes);
    }
}
