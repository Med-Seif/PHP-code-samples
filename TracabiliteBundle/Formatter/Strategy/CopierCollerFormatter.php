<?php


namespace Gta\TracabiliteBundle\Formatter\Strategy;


use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class CopierCollerFormatter
 * @author Abdessami (bennani.a@mipih.fr)
 * Date 21/11/2019 13:56
 * @package Gta\TracabiliteBundle\Formatter\Strategy
 */
class CopierCollerFormatter extends AbstractTracabiliteFormatter
{
    /**
     * Formater les données afin de les rendre prêtes pour une insertion dans la table (EGMHIST)
     *
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     * @throws \Gta\TracabiliteBundle\Exception\MessageDesciptionGenerationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
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
        return in_array($trigger, [ Tc::UC_COPIERCOLLER ]);
    }
}