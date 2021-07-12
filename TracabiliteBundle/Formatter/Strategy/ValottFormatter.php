<?php

namespace Gta\TracabiliteBundle\Formatter\Strategy;

use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;

/**
 * Class ValottFormatter
 *
 * @package Gta\TracabiliteBundle\Entity
 * @author  Seif <ben.s@mipih.fr>
 */
class ValottFormatter extends AbstractTracabiliteFormatter
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
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
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
        return false;
    }
}