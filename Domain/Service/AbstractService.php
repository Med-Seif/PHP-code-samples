<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 12/04/2019 19:24
 */

namespace Gta\Domain\Service;


use Gta\CoreBundle\ParamConverter\MainFilter;

/**
 * Class AbstractService
 *
 * @package Gta\MedicalBundle\Service
 * @author  Seif <ben.s@mipih.fr> (03/05/2019/ 16:50)
 * @version 19
 */
abstract class AbstractService
{
    /**
     * @var MainFilter
     */
    private $params;

    /**
     * @return \Gta\CoreBundle\ParamConverter\MainFilter
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $mainFilter
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function setParams(MainFilter $mainFilter)
    {
        $this->params = $mainFilter;
    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function getParamsArray()
    {
        return $this->params->toArray();
    }
}