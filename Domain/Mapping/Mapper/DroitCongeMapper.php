<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 16/04/2019 17:31
 */

namespace Gta\Domain\Mapping\Mapper;

/**
 * Class DroitCongeMapper
 *
 * @package Gta\MedicalBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (16/04/2019/ 17:31)
 * @version 19
 */
class DroitCongeMapper extends AbstractMapper
{

    /**
     * @param array $data
     * @param array $extra
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function mapData(array $data, array $extra = [])
    {
        // Vérifie que le jour de droit est dans Pdr la valeur sera à 0 si pcg2 à vide
        $getDroitJour = function ($isPdr, $jours, $datdeb, $datfin) {
            $jours = $isPdr ? $jours : 0;
            if (!$isPdr && null === $datdeb && null === $datfin) {
                $jours = $jours ? $jours : '';
            }

            return $jours;
        };

        // Vérifie que le reliquat est dans Pdr la valeur sera à 0 si pcg2 à vide
        $getReliquat = function ($droitJour, $reliquat) {
            if (!$droitJour) {
                return '';
            }
            return $reliquat ? $reliquat : 0;
        };

        $mapped = [];
        foreach ($data as $row) {
            $isPdr = isset($row['is_pdr']); // les données issues de PDR étant différentes de celles de PCG2 (voir le service droits congés)
            // date de début
            $datdeb = $isPdr ? $row['datdeb'] : null;
            $datfin = $isPdr ? $row['datfin'] : null;
            $droitJour = $getDroitJour($isPdr, $row['jours'], $datdeb, $datfin);
            $mapped[] = array(
                'matric'       => $row['matric'],
                'name'         => $row['name'],
                'category'     => $row['typpers'],
                'position'     => $row['position'],
                'motif'        => $row['motif'],
                'droit_jour' => $droitJour,
                'droit_heure'  => $isPdr ? $row['heures'] : 0,
                'periode'      => [
                    'datdeb' => $datdeb,
                    'datfin' => $datfin,
                ],
                'conge_pose'   => $isPdr ? $row['conge_pose'] : $row['jours'],
                'droit_cet'    => $isPdr ? $row['versement_jours'] : null,
                'reliquat' => $getReliquat($droitJour, $row['reliquat']),
                'commentaire'  => $isPdr ? $row['commentaire'] : null,
                'dranne'       => $row['dranne'],
                'typpersconge' => $row['typpersconge'],
            );
        }

        return $mapped;
    }

}