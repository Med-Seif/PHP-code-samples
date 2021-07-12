<?php

namespace Gta\TracabiliteBundle\Formatter\Strategy;

use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class AffectationFormatter
 *
 * @package Gta\TracabiliteBundle\Formatter\Strategy
 * @author  Seif <ben.s@mipih.fr>
 * @version 19
 */
class AffectationFormatter extends AbstractTracabiliteFormatter
{
    /**
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
        return $this->getDbEgmhistLogObject();
    }

    /**
     * @return string
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @author Seif <ben.s@mipih.fr>
     */
    public function generateMessage()
    {
        $keys = ['scppas', 'scpppm', 'scppri'];
        $val = [];
        foreach ($keys as $key) {
            $current = trim($this->getDbEgmhistLogObject()->getUcParams($key));
            if (in_array($current, ['O', 'N'])) {
                $val[$key] = $current;
            }
        }
        // Le message dans ce formateur et d'après l'ancien code, ne dépend pas de la table des TNO
        // et construit le message manuellement
        $typTabTrim = trim($this->getDbEgmhistLogObject()->getTyptab());
        if (in_array($typTabTrim, ['SEN', 'JUN'])) {
            return "Astreinte: "
                .$val['scppas'].", Mensuel: "
                .$val['scpppm'].", Service principal: "
                .$val['scppri'];
        }

        return 'message invalide';
    }

    /**
     * @param $trigger
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($trigger)
    {
        return in_array($trigger, [Tc::UC_AFFECTATION_AJOUT, Tc::UC_AFFECTATION_MAJ, Tc::UC_AFFECTATION_SUPP]);
    }
}