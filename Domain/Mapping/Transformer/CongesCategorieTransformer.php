<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 18/04/2019 16:45
 */

namespace Gta\Domain\Mapping\Transformer;


use Gta\Domain\Mapping\Mapper\DetailCongeMapper;
use Gta\Domain\Mapping\Mapper\DroitCongeMapper;

/**
 * Class CongesCategorieTransformer
 *
 * @package Gta\Domain\Mapping\Transformer
 * @author  Seif <ben.s@mipih.fr> (18/04/2019/ 19:37)
 * @version 19
 */
class CongesCategorieTransformer extends AbstractTnoTransformer
{
    const KEY = 'typpers';

    /**
     * @param $class
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($class)
    {
        return $class === DroitCongeMapper::class || $class === DetailCongeMapper::class;
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
        if (!isset($row[$this::KEY])) {
            return $row;
        }
        $typpers = $row[$this::KEY];
        // si le typpers n'existe pas dans le row ou égal à null ou n'a pas de libellé TNO
        if (!isset($transformerData[$typpers])) {
            return $row;
        }
        if(count(array_intersect([$row['position']], ['CET','CEP'])) > 0){
            $row[$this::KEY] = '-'; //si CET/CEP alors il n'y a pas de libellé de type de personnel
        }
        else
        // par convention on change jamais des clefs de tablea içi, c le boulot du mapper
        if ($typpers == $transformerData[$typpers]['tnoval']) {
            $row[$this::KEY] = $transformerData[$typpers]['tnolibs'];
        }

        return $row;
    }
}