<?php
/**
 * Created by PhpStorm.
 * User: lamrid.o
 * Date: 02/07/2019
 * Time: 14:16
 */

namespace Gta\Domain\Lib;


Trait MathUtilsTrait
{

    /**
     * @param $value
     * @param $min
     * @param $max
     *
     * @return bool
     */

    public  static  function  range($value, $min, $max){
        return ($value < $min || $value > $max);
    }
}