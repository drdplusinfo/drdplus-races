<?php
namespace DrdPlus\Tests\Races;

use DrdPlus\Races\Race;
use Granam\Tests\Exceptions\Tools\AbstractExceptionsHierarchyTest;

class ExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        $reflection = new \ReflectionClass(Race::class);

        return $reflection->getNamespaceName();
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

}
