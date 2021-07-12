<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 15/03/2019 19:52
 */

namespace Gta\Domain\Mapping\Transformer;

/**
 * Class AbstractDataTransformer
 *
 * @package Gta\MedicalBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (15/03/2019/ 19:53)
 * @version 19
 */
abstract class AbstractDataTransformer
{
    private $params = [];

    /**
     * Add an additionnal param
     *
     * @param $param
     * @param $value
     *
     * @return mixed|void
     * @author Seif <ben.s@mipih.fr>
     */
    public function addParam($param, $value)
    {
        $this->params[$param] = $value;
    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets parameters that will be send to the repository
     * Notice that params are merged and not replacing the origin ones
     *
     * @param array $params
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function setParams(array $params)
    {
        if (0 !== count($this->params)) {
            $this->params = array_merge($this->params, $params);

            return;
        }
        $this->params = $params;
    }

    /**
     * @param $class
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function supports($class);

    /**
     * Performs the desired transformation on one row
     * generally will be called inside a loop
     *
     * @param mixed $row
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function transform($row);

    /**
     * Sets data gathered from repositories (or wathever else) that will be used in look() method
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function initData();
}