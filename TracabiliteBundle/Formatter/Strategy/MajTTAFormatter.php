<?php

namespace Gta\TracabiliteBundle\Formatter\Strategy;

use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class MajTTAFormatter
 *
 * @package Gta\TracabiliteBundle\Entity
 * @author  Seif <ben.s@mipih.fr>
 */
class MajTTAFormatter extends AbstractTracabiliteFormatter
{

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     */
    public function format()
    {
        $this->getDbEgmhistLogObject()->setTyptab(' ');
        $this->getDbEgmhistLogObject()->setTyptab($this->getDbEgmhistLogObject()->getUcParams('typtabTTA'));

        return $this->getDbEgmhistLogObject();
    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     */
    public function generateMessage()
    {
        $type = ($this->getDbEgmhistLogObject()->getUcParams('type') === "recup") ? "Récupération" : "Payé";
        $tnolibl = $this->generateLibAction();
        $patterns = [];
        $patterns[0] = '[TYPE]'; // Type Payé ou Recupéré
        $patterns[1] = '[VAL]'; // Nombre, la valeur saisie
        $replacements = array();
        $replacements[0] = $type;
        $replacements[1] = $this->getDbEgmhistLogObject()->getUcParams('val');
        $val = preg_replace($patterns, $replacements, $tnolibl);

        return "Mise à jour TTA :".$val;
    }

    public function supports($trigger)
    {
        return $trigger === Tc::UC_VALO_TT_MAJ_TTA;
    }
}