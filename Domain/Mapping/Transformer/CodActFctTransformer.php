<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 11/04/2019 15:41
 */

namespace Gta\Domain\Mapping\Transformer;

use Gta\Domain\Mapping\Mapper\ActionMapper;

/**
 * Class CodActFctTransformer
 *
 * @package Gta\Domain\Mapping\Transformer
 * @author  Seif <ben.s@mipih.fr> (11/04/2019/ 15:42)
 * @version 19
 */
class CodActFctTransformer extends AbstractTnoTransformer
{

    /**
     * @param $class
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($class)
    {
        return $class === ActionMapper::class;
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
        $transformerData = $this->getNomenclatureData();
        $row['action'] = isset($transformerData[$row['codact']]['tnolibs']) ? trim($transformerData[$row['codact']]['tnolibs']): '';
        $row['theme'] = isset($transformerData[$row['codact']]['tnolibl']) ? trim($transformerData[$row['codact']]['tnolibl']): '';

        return $row;

    }
}