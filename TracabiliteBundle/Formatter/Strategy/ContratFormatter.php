<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 30/11/2018 09:07
 */

namespace Gta\TracabiliteBundle\Formatter\Strategy;


use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class ContratFormatter
 *
 * @package Gta\TracabiliteBundle\Formatter\Strategy
 * @author  Seif <ben.s@mipih.fr>
 * @version 19
 */
class ContratFormatter extends AbstractTracabiliteFormatter
{
    /**
     * @return array|\Gta\TracabiliteBundle\Entity\EgmhistLogObject
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
        return in_array($trigger, [Tc::UC_CONTRAT_AJOUT, Tc::UC_CONTRAT_MAJ, Tc::UC_CONTRAT_SUPP]);
    }
}