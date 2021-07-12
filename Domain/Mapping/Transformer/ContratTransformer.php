<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 15/03/2019 10:21
 */

namespace Gta\Domain\Mapping\Transformer;


use Gta\Domain\Mapping\Transformer\AbstractTnoTransformer;
use Gta\Domain\Mapping\Mapper\ContratMapper;

/**
 * Class ContratTransformer
 *
 * @package Gta\Domain\Mapping\Transformer
 * @author  Seif <ben.s@mipih.fr> (15/03/2019/ 17:45)
 * @version 19
 */
class ContratTransformer extends AbstractTnoTransformer
{

    /**
     * @param $class
     *
     * @return bool|mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($class)
    {
        return ContratMapper::class === $class;
    }

    /**
     * @param $row
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function transform($row)
    {
        $transformerData = $this->getNomenclatureData();

        $getTypConLib = function () use ($row, $transformerData) {
            if ($transformerData['TYPCON']['tnoval2'] == $row['typcon']) {
                return $transformerData['TYPCON']['tnolibs'];
            }

            return null;
        };
        $getObjConLib = function () use ($row, $transformerData) {
            return $transformerData[$row['objcon']]['tnolibs'];
        };

        $row['typcon_lib'] = $getTypConLib();
        $row['objcon_lib'] = $getObjConLib();

        return $row;
    }

}