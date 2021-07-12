<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 09/04/2019 11:54
 */

namespace Gta\Domain\Mapping\Mapper;

/**
 * Un mapper par défaut qui applique les transformers enregistrés
 * sans un travail supplémentaire de formatage
 *
 * @package Gta\MedicalBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (09/04/2019/ 11:55)
 * @version 19
 */
class DefaultMapper extends AbstractMapper
{

    /**
     * @param array $data
     * @param array $extra
     *
     * @author Seif <ben.s@mipih.fr>
     * @return array
     */
    public function mapData(array $data, array $extra = [])
    {
        $mapped = [];
        foreach ($data as $key => $row) {
            $mapped[$key] = $row;
        }

        return $mapped;
    }
}