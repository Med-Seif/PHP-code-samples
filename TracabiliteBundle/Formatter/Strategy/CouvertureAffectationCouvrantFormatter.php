<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 26/06/2019 16:44
 */

namespace Gta\TracabiliteBundle\Formatter\Strategy;


use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class CouvertureAffectationCouvrantFormatter
 *
 * @package Gta\TracabiliteBundle\Formatter\Strategy
 * @author  Seif <ben.s@mipih.fr> (26/06/2019/ 16:54)
 * @version 19
 */
class CouvertureAffectationCouvrantFormatter extends AbstractTracabiliteFormatter
{
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
     * @author Seif <ben.s@mipih.fr>
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
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
        return $trigger === Tc::UC_COUVERTURE_COUVRANT_AFFECT_ADD || $trigger === Tc::UC_COUVERTURE_COUVRANT_AFFECT_SUPP;
    }

}