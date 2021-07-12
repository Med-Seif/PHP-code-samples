<?php

namespace Gta\TracabiliteBundle\Log\Handler;

use Gta\CoreBundle\DataBase\DbConnectionAwareInterface;
use Gta\CoreBundle\DataBase\DbConnectionTrait;
use Gta\CoreBundle\Repository\BaseRepository;
use Gta\Domain\Lib\Std;
use Gta\TracabiliteBundle\Resources\StringConstants as Sc;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class DbTableLogHandler
 *
 * @package Gta\TracabiliteBundle\Log\Handler
 * @author  Seif <ben.s@mipih.fr>
 */
class EgmhistLogHandler extends AbstractHandler implements LoggerAwareInterface, DbConnectionAwareInterface
{
    use LoggerAwareTrait, DbConnectionTrait;
    /**
     * @var int used for profiling logging messages
     */
    private static $counter = 0;

    /**
     * {@inheritdoc}
     *
     * @author Seif <ben.s@mipih.fr>
     */
    protected function write(array $record)
    {
        $this->logger->log(Logger::DEBUG, 'Writing process start', [__CLASS__, __FUNCTION__]);
        if (!isset($record['formatted'])) {
            $this->logger->log(Logger::DEBUG, 'Formatted data is equal to NULL');

            return; // on a pas subi un formatage au préalable
        }
        $formatted = $record['formatted'];
        if (false === $formatted) {
            $this->logger->log(Logger::DEBUG, 'Formatted data is equal to FALSE');

            return;
        }
        // insertion d'une seule ligne
        if (!array_key_exists('collection', $formatted)) {
            $this->logger->log(Logger::DEBUG, 'Writing one row');
            $this->writeOneRow($formatted);

            return;
        }
        // insertion multiple d'une collection
        $this->logger->log(Logger::DEBUG, 'Writing Collection');
        foreach ($formatted['collection'] as $row) {


            $this->writeOneRow($row);
        }
    }

    /**
     * @param $row
     *
     * @author Seif <ben.s@mipih.fr>
     */
    private function writeOneRow($row)
    {
        // regénerer le temps courant à chaque insertion
        $timestamp = \DateTime::createFromFormat('U.u', microtime(true))
            ->setTimezone(new \DateTimeZone('Europe/Paris'))
            ->format('d/m/Y H:i:s.u');
        $row[Sc::PARAMETERS_KEY]['dateff'] = $timestamp;
        $row[Sc::PLACE_HOLDERS_KEY]['dateff'] = 'to_timestamp('
            .BaseRepository::DEFAULT_PLACE_HOLDER
            .'dateff'
            .',\'DD/MM/YYYY HH24:MI:SS.FF\')';
        $qb = $this->getDbConnection()->createQueryBuilder();
        $qb
            ->insert($this->getFormatter()->getDbTableName())
            ->values($row[Sc::PLACE_HOLDERS_KEY])
            ->setParameters($row[Sc::PARAMETERS_KEY])
            ->execute();
            self::$counter++;
        $this->logger->log(
            Logger::DEBUG,
            'Row number '.self::$counter.' was written with success',
            [$qb->getSQL(), $qb->getParameters()]
        );
    }
}
