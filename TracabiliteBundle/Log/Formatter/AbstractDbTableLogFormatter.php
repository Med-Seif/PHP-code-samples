<?php

namespace Gta\TracabiliteBundle\Log\Formatter;

use Gta\TracabiliteBundle\Exception\UndefinedTableName;
use Monolog\Formatter\FormatterInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractDbTableLogFormatter
 *
 * @package Gta\TracabiliteBundle\Log\Formatter
 * @author  Seif <ben.s@mipih.fr>
 */
abstract class AbstractDbTableLogFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    private $dbTableName;
    /**
     * @var \Gta\TracabiliteBundle\Serializer\DbEgmhistLogObject\Serializer
     */
    private $serializer;
    /**
     * @var array List of formatters wich will be used in second level formatting (not monolog formatters!)
     */
    private $subscribedFormattingStrategies;

    /**
     * AbstractDbTableLogFormatter constructor.
     *
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     *
     * @return mixed
     * @throws \Gta\TracabiliteBundle\Exception\UndefinedTableName
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    final public function getDbTableName()
    {
        if (is_null($this->dbTableName)) {
            throw new UndefinedTableName(UndefinedTableName::MESSAGE);
        }

        return $this->dbTableName;
    }

    /**
     * @param $dbTableName
     *
     * @author Seif <ben.s@mipih.fr>
     */
    final public function setDbTableName($dbTableName)
    {
        $this->dbTableName = $dbTableName;
    }

    /**
     * @return \Gta\TracabiliteBundle\Serializer\DbEgmhistLogObject\Serializer
     */
    final public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    final public function getSubscribedFormattingStrategies()
    {
        return $this->subscribedFormattingStrategies;
    }

    /**
     * @param array $formatters
     *
     * @author Seif <ben.s@mipih.fr>
     */
    final public function setSubscribedFormattingStrategies(array $formatters)
    {
        $this->subscribedFormattingStrategies = $formatters;
    }

}