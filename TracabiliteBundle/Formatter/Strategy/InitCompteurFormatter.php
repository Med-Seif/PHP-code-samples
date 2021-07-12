<?php

namespace Gta\TracabiliteBundle\Formatter\Strategy;

use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class InitCompteurFormatter
 *
 * @package Gta\TracabiliteBundle\Entity
 * @author  Seif <ben.s@mipih.fr>
 */
class InitCompteurFormatter extends AbstractTracabiliteFormatter
{
    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
        return $this->getDbEgmhistLogObject();
    }

    /**
     * @return mixed
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @author Seif <ben.s@mipih.fr>
     */
    public function generateMessage()
    {
        $text = '';
        // revoir cette partie
//        if (TracabilteConfig::OPERATION_TYPE_INSERT == $this->getDbEgmhistLogObject()->getOperationType()) {
//            $text = "activité de soirée - Création : ".$this->getDbEgmhistLogObject()->getDbParams('durmin');
//        } elseif (TracabilteConfig::OPERATION_TYPE_DELETE) {
//            $text = "activité de soirée - Suppression ";
//        }
        $tnolibl = $this->generateLibAction();
        $patterns = [];
        $patterns[0] = '[MODE]';
        $replacements = [];
        $replacements[0] = $text;

        return "Initialisation des compteurs :".preg_replace($patterns, $replacements, $tnolibl);
    }

    public function supports($trigger)
    {
        return $trigger === Tc::UC_VALO_TT_INIT_COMPTEUR;
    }
}