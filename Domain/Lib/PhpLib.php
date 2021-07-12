<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 16/05/2019 09:45
 */

namespace Gta\Domain\Lib;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Column;

/**
 * Trait PhpLib
 * @package Gta\Domain\Lib
 * @author  Seif <ben.s@mipih.fr> (16/05/2019/ 09:45)
 * @version 19
 */
trait PhpLib
{
    /**
     * Returns a constant name by its value
     *
     * @param $class
     * @param $value
     *
     * @return mixed
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public static function getConstantName($class, $value)
    {
        $class = new \ReflectionClass($class);
        $constants = array_flip($class->getConstants());
        if (!isset($constants[$value])) {
            return null;
        }

        return $constants[$value];
    }

    /**
     * Retreives column names from an entity class based on its annotations
     *
     * @param      $object
     *
     * @param bool $reversed if we return columns properties as keys (true) or ad values in the result array (false)
     *
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public static function getColumnsProperties($object, $reversed = false)
    {
        $className = get_class($object);
        $refClass = new \ReflectionClass($className);
        $properties = $refClass->getProperties();
        $columnProperties = [];
        foreach ($properties as $property) {
            $refProperty = new \ReflectionProperty($className, $property->getName());
            # if a property is private or protected then it will be impossible to work with
            if (!$refProperty->isPublic()) {
                $refProperty->setAccessible(true);
            }
            # there's a service called ANnotatoinReader in symfony that is very simple and can work as desired without the need of injecting something in it
            $annotationReader = new AnnotationReader();
            /** @var \Doctrine\ORM\Mapping\Column $columnAnnotation */
            $columnAnnotation = $annotationReader->getPropertyAnnotation(
                $refProperty,
                Column::class
            ); # get only the column annotation
            if ($columnAnnotation instanceof Column) {
                $columnProperties[] = $property->getName();
            }
        }
        if (true === $reversed) {
            return array_flip($columnProperties);
        }

        return $columnProperties;
    }
}