<?php


namespace Gta\Domain\Mapping\Transformer;


use Gta\Domain\Mapping\Mapper\MultiPoseCopierCollerMapper;
use Gta\MedicalBundle\Model\ControlMultiPoseCopierCollerMessage;

/**
 * Class MulitPoseCopierCollerErreurTransformer
 * @author Abdessami (bennani.a@mipih.fr)
 * Date 12/12/2019 11:53
 * @package Gta\Domain\Mapping\Transformer
 */
class MulitPoseCopierCollerErreurTransformer extends AbstractDataTransformer
{

    /**
     * @param $class
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($class)
    {
        return $class === MultiPoseCopierCollerMapper::class;
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
        $row['errorMsg'] = $row['dateff'].' ('.$row['horaire'].') - '.$row['message'];
        return $row;
    }

    /**
     * Sets data gathered from repositories (or wathever else) that will be used in look() method
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function initData()
    {

    }
}