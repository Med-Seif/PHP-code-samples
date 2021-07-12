<?php


namespace Gta\TracabiliteBundle\Formatter\Strategy;


use Gta\TracabiliteBundle\Entity\EgmhistLogObject;
use Gta\TracabiliteBundle\Entity\EgmhistLogObjectCollection;
use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;
use phpDocumentor\Reflection\Types\Self_;

/**
 * Class MultiPoseFormatter
 * @author Abdessami (bennani.a@mipih.fr)
 * Date 20/11/2019 10:16
 * @package Gta\TracabiliteBundle\Formatter\Strategy
 */
class MultiPoseFormatter extends AbstractTracabiliteFormatter
{
    /**
     * Formater les données afin de les rendre prêtes pour une insertion dans la table (EGMHIST)
     * @return array|\Gta\TracabiliteBundle\Entity\EgmhistLogObject|EgmhistLogObjectCollection
     * @throws \Gta\TracabiliteBundle\Exception\MessageDesciptionGenerationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @throws \ReflectionException
     * @author Abdessami (bennani.a@mipih.fr)
     * Date 20/11/2019 16:17
     */
    public function format()
    {
        $UcParams = $this->getDbEgmhistLogObject()->getUcParams();

        $UcParams = array_merge($UcParams);
        $this->getDbEgmhistLogObject()->setUcParams($UcParams);
        $this->getDbEgmhistLogObject()->setMessage($this->generateMessage());  // générer un message pour la ligne.
        return $this->getDbEgmhistLogObject();
    }

    /**
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
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($trigger)
    {
        return in_array($trigger, [ Tc::UC_MULTIPOSE ]);
    }
}