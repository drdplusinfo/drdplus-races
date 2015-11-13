<?php
namespace DrdPlus\Tests\Races\Dwarfs;

use DrdPlus\Codes\PropertyCodes;
use DrdPlus\Tests\Races\AbstractTestOfRace;

abstract class AbstractTestOfDwarf extends AbstractTestOfRace
{
    protected function getExpectedOtherProperty($propertyCode)
    {
        $properties = [
            PropertyCodes::SENSES => -1,
            PropertyCodes::TOUGHNESS => 1,
            PropertyCodes::SIZE => 0,
            PropertyCodes::WEIGHT_IN_KG => 70.0,
            PropertyCodes::HEIGHT_IN_CM => 140.0,
            PropertyCodes::INFRAVISION => true,
            PropertyCodes::NATIVE_REGENERATION => false,
            PropertyCodes::REQUIRES_DM_AGREEMENT => false,
        ];

        return $properties[$propertyCode];
    }

    protected function getExpectedRemarkableSense()
    {
        return PropertyCodes::TOUCH;
    }

}
