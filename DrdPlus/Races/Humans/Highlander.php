<?php
namespace DrdPlus\Races\Humans;

class Highlander extends Human
{
    const HIGHLANDER = 'highlander';

    public static function getIt()
    {
        return parent::getIt(self::HIGHLANDER);
    }

    public function getSubraceCode()
    {
        return self::HIGHLANDER;
    }

}
