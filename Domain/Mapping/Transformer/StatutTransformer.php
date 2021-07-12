<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 24/04/2019 20:46
 */

namespace Gta\Domain\Mapping\Transformer;


use Gta\Domain\Mapping\Mapper\SearchIntervenantMapper;

/**
 * Class StatutTransformer
 *
 * @package Gta\Domain\Mapping\Transformer
 * @author  Seif <ben.s@mipih.fr> (24/04/2019/ 20:48)
 * @version 19
 */
class StatutTransformer extends AbstractTnoTransformer
{

    /**
     * @param $class
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($class)
    {
        return SearchIntervenantMapper::class === $class;
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
        $row['statut_lib'] = (isset($transformerData[$row['statut']]['tnolibs'])) ? $transformerData[$row['statut']]['tnolibs'] : '';

        return $row;
    }
}