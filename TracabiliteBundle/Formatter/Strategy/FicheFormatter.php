<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 06/11/2018 11:18
 */

namespace Gta\TracabiliteBundle\Formatter\Strategy;

use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

class FicheFormatter extends AbstractTracabiliteFormatter
{
    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
        $datfin = $this->getDbEgmhistLogObject()->getDatfin();
        $this->getDbEgmhistLogObject()->setDatfin(
            (isset($datfin)) ? $datfin : '30001231'
        );

        return $this->getDbEgmhistLogObject();
    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function generateMessage()
    {
        $patterns = [];
        $patterns[0] = '[M]';
        $patterns[1] = '[P]';
        $replacements = [];
        $replacements[0] = "[".$this->getDbEgmhistLogObject()->getMatric()."]";
        $replacements[1] = "[".$this->getDbEgmhistLogObject()->getServic()."]";
        $tnolibl = $this->generateLibAction();

        return str_replace($patterns, $replacements, $tnolibl);
    }

    public function supports($trigger)
    {
        return in_array(
            $trigger,
            array(
                Tc::UC_FICHE_NOUV,
                Tc::UC_FICHE_AJOUT,
                Tc::UC_FICHE_MAJ,
                Tc::UC_FICHE_SUPP,
            )
        );
    }
}