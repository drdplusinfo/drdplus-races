<?php
namespace DrdPlus\Tests\Races;

use Drd\Genders\Female;
use Drd\Genders\Gender;
use Drd\Genders\Male;
use DrdPlus\Codes\PropertyCodes;
use DrdPlus\Races\Dwarfs\CommonDwarf;
use DrdPlus\Races\Race;
use DrdPlus\Tables\Tables;

abstract class AbstractTestOfRace extends TestWithMockery
{
    /**
     * @test
     * @return Race
     */
    public function I_can_get_race()
    {
        $subraceClass = $this->getSubraceClass();
        $subrace = $subraceClass::getIt();
        $this->assertInstanceOf($subraceClass, $subrace);
        $this->assertSame($this->getRaceCode(), $subrace->getRaceCode());
        $this->assertSame($this->getSubraceCode(), $subrace->getSubraceCode());

        return $subrace;
    }

    /**
     * @return string|Race|CommonDwarf
     */
    protected function getSubraceClass()
    {
        return preg_replace('~[\\\]Tests(.+)Test$~', '$1', static::class);
    }

    /**
     * @return string
     */
    protected function getSubraceCode()
    {
        $subraceCode = str_replace($this->getRaceCode(), '', strtolower($this->getSubraceBaseName()));

        return $subraceCode;
    }

    /**
     * @return string
     */
    protected function getSubraceBaseName()
    {
        $subraceClass = $this->getSubraceClass();

        return preg_replace('~(\w+\\\)*(\w+)~', '$2', $subraceClass);
    }

    /**
     * @return string
     */
    protected function getRaceCode()
    {
        $baseNamespace = $this->getSubraceBaseNamespace();
        $singular = preg_replace('~s$~', '', $baseNamespace);

        return strtolower($singular);
    }

    protected function getSubraceBaseNamespace()
    {
        $namespace = $this->getSubraceNamespace();

        return preg_replace('~(\w+\\\)*(\w+)~', '$2', $namespace);
    }

    protected function getSubraceNamespace()
    {
        $subraceClass = $this->getSubraceClass();

        return preg_replace('~\\\[\w]+$~', '', $subraceClass);
    }

    /**
     * @test
     * @depends I_can_get_race
     *
     * @param Race $race
     */
    public function I_can_get_base_property(Race $race)
    {
        $tables = new Tables();
        foreach ($this->getGenders() as $gender) {
            foreach ($this->getPropertyCodes() as $propertyCode) {
                $sameValueByGenericGetter = $race->getProperty($propertyCode, $tables, $gender);
                switch ($propertyCode) {
                    case PropertyCodes::STRENGTH :
                        $value = $race->getStrength($gender, $tables);
                        break;
                    case PropertyCodes::AGILITY :
                        $value = $race->getAgility($gender, $tables);
                        break;
                    case PropertyCodes::KNACK :
                        $value = $race->getKnack($gender, $tables);
                        break;
                    case PropertyCodes::WILL :
                        $value = $race->getWill($gender, $tables);
                        break;
                    case PropertyCodes::INTELLIGENCE :
                        $value = $race->getIntelligence($gender, $tables);
                        break;
                    case PropertyCodes::CHARISMA :
                        $value = $race->getCharisma($gender, $tables);
                        break;
                    default :
                        $value = null;
                }
                $this->assertSame(
                    $this->getExpectedBaseProperty($gender->getEnumValue(), $propertyCode),
                    $value,
                    "Unexpected {$gender} $propertyCode"
                );
                $this->assertSame($sameValueByGenericGetter, $value);
            }
        }
    }

    /**
     * @return array|Gender[]
     */
    private function getGenders()
    {
        return [
            Male::getIt(),
            Female::getIt(),
        ];
    }

    /**
     * @return array|string[]
     */
    private function getPropertyCodes()
    {
        return [
            PropertyCodes::STRENGTH,
            PropertyCodes::AGILITY,
            PropertyCodes::KNACK,
            PropertyCodes::WILL,
            PropertyCodes::INTELLIGENCE,
            PropertyCodes::CHARISMA,
        ];
    }

    /**
     * @param string $genderCode
     * @param string $propertyCode
     *
     * @return int
     */
    abstract protected function getExpectedBaseProperty($genderCode, $propertyCode);


    /**
     * @test
     * @depends I_can_get_race
     * @expectedException \DrdPlus\Races\Exceptions\UnknownPropertyCode
     *
     * @param Race $race
     */
    public function I_can_not_get_property_by_its_invalid_code(Race $race)
    {
        $tables = new Tables();
        /** @var Gender $gender */
        $gender = \Mockery::mock(Gender::class);
        $race->getProperty('invalid code', $tables, $gender);
    }

    /**
     * @test
     * @depends I_can_get_race
     *
     * @param Race $race
     */
    public function I_can_get_non_base_property(Race $race)
    {
        $tables = new Tables();
        $racesTable = $tables->getRacesTable();
        foreach ($this->getGenders() as $gender) {
            foreach ($this->getNonBasePropertyCodes() as $propertyCode) {
                $sameValueByGenericGetter = $race->getProperty($propertyCode, $tables, $gender);
                switch ($propertyCode) {
                    case PropertyCodes::SENSES :
                        $value = $race->getSenses($racesTable);
                        break;
                    case PropertyCodes::TOUGHNESS :
                        $value = $race->getToughness($racesTable);
                        break;
                    case PropertyCodes::SIZE :
                        $value = $race->getSize($gender, $tables);
                        break;
                    case PropertyCodes::WEIGHT_IN_KG :
                        $value = $race->getWeightInKg($gender, $tables);
                        break;
                    case PropertyCodes::HEIGHT_IN_CM :
                        $value = $race->getHeightInCm($racesTable);
                        break;
                    case PropertyCodes::INFRAVISION :
                        $value = $race->hasInfravision($racesTable);
                        break;
                    case PropertyCodes::NATIVE_REGENERATION :
                        $value = $race->hasNativeRegeneration($racesTable);
                        break;
                    case PropertyCodes::REQUIRES_DM_AGREEMENT :
                        $value = $race->requiresDmAgreement($racesTable);
                        break;
                    case PropertyCodes::REMARKABLE_SENSE :
                        $value = $race->getRemarkableSense($racesTable);
                        break;
                    default :
                        $value = null;
                }
                $this->assertSame(
                    $this->getExpectedOtherProperty($propertyCode, $gender->getEnumValue()),
                    $value,
                    "Unexpected {$gender} $propertyCode"
                );
                $this->assertSame($sameValueByGenericGetter, $value);
            }
        }
    }

    private function getNonBasePropertyCodes()
    {
        return [
            PropertyCodes::SENSES,
            PropertyCodes::TOUGHNESS,
            PropertyCodes::SIZE,
            PropertyCodes::WEIGHT_IN_KG,
            PropertyCodes::HEIGHT_IN_CM,
            PropertyCodes::INFRAVISION,
            PropertyCodes::NATIVE_REGENERATION,
            PropertyCodes::REQUIRES_DM_AGREEMENT,
            PropertyCodes::REMARKABLE_SENSE,
        ];
    }

    /**
     * @param string $propertyCode
     * @param string $genderCode
     * @return int|float|bool|string
     */
    abstract protected function getExpectedOtherProperty($propertyCode, $genderCode);
}
