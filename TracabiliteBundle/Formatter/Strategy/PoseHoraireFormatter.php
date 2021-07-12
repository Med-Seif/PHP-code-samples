<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 26/02/2019 09:07
 */

namespace Gta\TracabiliteBundle\Formatter\Strategy;


use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class PoseHoraireFormatter
 *
 * @package Gta\TracabiliteBundle\Formatter\Strategy
 * @author  Seif <ben.s@mipih.fr> (23/12/2019/ 17:39)
 * @version 19
 */
class PoseHoraireFormatter extends AbstractTracabiliteFormatter
{
    /*
     * Pose horaire :
        [loc] : [dat], [typh], Activité : [act], [rem], UF : [uf]

        Suppression horaire :
        [loc] : [dat], [typh], Activité : [act], [rem], UF : [uf]

     */

    /**
     * Formater les données afin de les rendre prêtes pour une insertion dans la BD (EGMHIST)
     *
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
        return $this->getDbEgmhistLogObject();
    }

    /**
     * Génération message de traçabilité à insérer dans la colonne value
     *
     * @return string
     * @throws \Gta\TracabiliteBundle\Exception\MessageDesciptionGenerationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     * @author tditt
     */
    public function generateMessage()
    {
        return $this->generateLibAction();
    }

    /**
     * @param $trigger
     *
     * @return bool|mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($trigger)
    {
        return in_array(
            $trigger,
            [
                Tc::UC_ACTIVITE_POSE_HORAIRE,
                Tc::UC_ACTIVITE_SUPPRESSION,
                Tc::UC_ACTIVITE_MAJ_HORAIRE,
                Tc::UC_ACTIVITE_POSE_CONGE,
                Tc::UC_ACTIVITE_SUPP_CONGE,
            ]
        );
    }

}