<?php


namespace Gta\Domain\Mapping\Mapper;

use Gta\MedicalBundle\Model\ControlMessage;

/**
 * Class MultiPoseCopierCollerMapper
 * @author Abdessami (bennani.a@mipih.fr)
 * Date 12/12/2019 11:53
 * @package Gta\Domain\Mapping\Mapper
 */
class MultiPoseCopierCollerMapper extends AbstractMapper
{
    // Attributs listant les erreurs remontées à l'utilisateur
    private $displayedErrors = [
        'ETAT_SRV_DRT_USER',
        'POP_INTERDITE_ERROR',
        'POP_INTERDITE_ALARM',
        'PARAM_ACT_HOR',
        'PARAM_ACT_HOR_2',
        'PRES_CGAB',
        'ACT_PRES_LIEE_CG',
        'PARAM_DBL_AFF',
        'ACT_PRES_LIEE_DPL',
        'ACT_PRES_LIEE_DPLX',
        'ACT_PRES_LIEE_COUV'
    ];

    // Attributs à titre indicatif listant les erreurs qui ne seront pas remontées
    private $silencedErrors = [
        'PARAM_ACT_BES'
    ];

    /**
     * @param array $data
     * @param array $extra
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function mapData(array $data, array $extra = [])
    {
        $mapped = [];

        foreach ($this->cleanData($data) as $values)
        {
            if(!isset($mapped[$values['matric']])) {
                $mapped[$values['matric']] = array('etcv' => $extra['nom'].' '.$extra['prenom'],
                                                   'msgs' => array());
            }

            $mapped[$values['matric']]['msgs'][] = $values['errorMsg'];
        }

        return array_values($mapped);
    }

    /**
     * @param array $data
     * @return array
     */
    private function cleanData(array $data)
    {
        $cleaned = [];

        foreach($data as $values){
            if(in_array($values['erreur'], $this->displayedErrors)){
                $cleaned[] = $values;
            }
        }

        return $cleaned;
    }
}