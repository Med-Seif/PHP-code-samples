<?php

namespace Gta\TracabiliteBundle\Formatter\Strategy;

use Gta\TracabiliteBundle\Entity\EgmhistLogObjectCollection;
use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class DeplacementFormatter
 *
 * @package Gta\TracabiliteBundle\Entity
 * @author  Seif <ben.s@mipih.fr>
 * @version 19
 */
class DeplacementFormatter extends AbstractTracabiliteFormatter
{
    // Ajout déplacement: [loc]: [dat], [typh], [act], [hdeb]-[hfin], [com], [typeplanning]
    // Mise à jour déplacement: [loc]: [dat], [typh], [act], [hdeb]-[hfin], [com], [typeplanning]
    // Supression déplacement: [loc]: [dat], [typh], [act], [hdeb]-[hfin] - [com], [typeplanning]

    /**
     * @return array|\Gta\TracabiliteBundle\Entity\EgmhistLogObject|\Gta\TracabiliteBundle\Entity\EgmhistLogObjectCollection
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @author Seif <ben.s@mipih.fr>
     */
    const EXTRA = 'extra';

    /**
     * @return array|\Gta\TracabiliteBundle\Entity\EgmhistLogObject|\Gta\TracabiliteBundle\Entity\EgmhistLogObjectCollection
     * @throws \Gta\TracabiliteBundle\Exception\MessageDesciptionGenerationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
        $UcParams = $this->getDbEgmhistLogObject()->getUcParams();
        if (!isset($UcParams[self::EXTRA])) {
            return $this->getDbEgmhistLogObject();
        }
        $count = count($UcParams[self::EXTRA]);
        if (0 !== $count) {
            $collection = new EgmhistLogObjectCollection();
            $count = count($UcParams[self::EXTRA]);
            for ($i = 0; $i < $count; $i++) {
                $UcParams = array_merge($UcParams, $UcParams[self::EXTRA][$i]);
                $this->getDbEgmhistLogObject()->setUcParams($UcParams);
                $this->getDbEgmhistLogObject()->setMessage($this->generateMessage()); // générer un message pour chaque ligne
                $collection[] = clone $this->getDbEgmhistLogObject();
            }

            return $collection;
        }

        return $this->getDbEgmhistLogObject();
    }

    /**
     * {@inheritdoc}
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
        return in_array($trigger, [Tc::UC_DEPLACEMENT_SUPP_AUTO, Tc::UC_DEPLACEMENT_AJOUT, Tc::UC_DEPLACEMENT_MAJ, Tc::UC_DEPLACEMENT_SUP]);
    }
}