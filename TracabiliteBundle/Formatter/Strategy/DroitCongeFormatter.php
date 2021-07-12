<?php

namespace Gta\TracabiliteBundle\Formatter\Strategy;

use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class DroitCongeFormatter
 *
 * @package Gta\TracabiliteBundle\Entity
 * @author  Seif <ben.s@mipih.fr>
 */
class DroitCongeFormatter extends AbstractTracabiliteFormatter
{
    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
        $this->getDbEgmhistLogObject()->setTyptab(' ');

        return $this->getDbEgmhistLogObject();
    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     * @return string
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     */
    public function generateMessage()
    {
        $tnolibl = $this->generateLibAction();

        return trim($tnolibl)
            .' Position / Motif : '
            .$this->getDbEgmhistLogObject()->getUcParams('dr1pos')
            .'-'
            .$this->getDbEgmhistLogObject()->getUcParams('dr1mot')
            .' pour l\'année : '
            .$this->getDbEgmhistLogObject()->getUcParams('dranne');

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
            array(
                Tc::UC_DROIT_CONGE_AJOUT,
                Tc::UC_DROIT_CONGE_MAJ,
                Tc::UC_DROIT_CONGE_SUPP,
            )
        );
    }
}