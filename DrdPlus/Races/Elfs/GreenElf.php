<?php
namespace DrdPlus\Races\Elfs;

class GreenElf extends Elf
{
    const GREEN = 'green';

    public static function getIt()
    {
        return parent::getItBySubrace(self::GREEN);
    }

    public function getSubraceCode()
    {
        return self::GREEN;
    }

}
