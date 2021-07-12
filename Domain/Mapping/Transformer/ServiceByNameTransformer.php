<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 15/03/2019 10:20
 */

namespace Gta\Domain\Mapping\Transformer;

/**
 * Class ServiceByNameTransformer
 *
 * @package Gta\Domain\Mapping\Transformer
 * @author  Seif <ben.s@mipih.fr> (15/03/2019/ 19:14)
 * @version 19
 */
class ServiceByNameTransformer extends AbstractDataTransformer
{

    /**
     * @param $class
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($class)
    {
        // TODO: Implement supports() method.
    }

    /**
     * Performs the desired transformation on one row
     * generally will be called inside a loop
     *
     * @param mixed $row
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function transform($row)
    {
        // TODO: Implement look() method.
    }

    /**
     * Sets data gathered from repositories (or wathever else) that will be used in look() method
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function initData()
    {
        // TODO: Implement initLookedData() method.
    }
}