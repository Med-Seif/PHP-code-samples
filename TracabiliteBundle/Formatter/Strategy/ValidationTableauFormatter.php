<?php

namespace Gta\TracabiliteBundle\Formatter\Strategy;

use Gta\MedicalBundle\Repository\GmesecRepository;
use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;
class ValidationTableauFormatter extends AbstractTracabiliteFormatter
{
    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
        $this->getDbEgmhistLogObject()->setTyptab(' ');
        $this->getDbEgmhistLogObject()->setMatric(' ');

        return $this->getDbEgmhistLogObject();
    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function generateMessage()
    {
        return "Validation du tableau de service";
    }

    public function supports($trigger)
    {
        return $trigger === Tc::UC_VALIDATION_TABLEAU;
    }
}