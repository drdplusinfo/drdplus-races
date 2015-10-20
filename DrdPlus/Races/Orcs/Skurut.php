<?php
namespace DrdPlus\Races\Orcs;

class Skurut extends Orc
{
    const SKURUT = 'skurut';

    public static function getIt()
    {
        return parent::getIt(self::SKURUT);
    }

    public function getSubraceCode()
    {
        return self::SKURUT;
    }

}
