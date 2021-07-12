<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 12/03/2019 15:43
 */

namespace Gta\TracabiliteBundle\Log\Handler;

use Gta\CoreBundle\DataBase\DbConnectionAwareInterface;
use Gta\CoreBundle\DataBase\DbConnectionTrait;
use Gta\TracabiliteBundle\Resources\StringConstants as Sc;

/**
 * Class ApiJournalLogHandler
 *
 * @package Gta\TracabiliteBundle\Log\Handler
 * @author  Seif <ben.s@mipih.fr> (12/03/2019/ 16:08)
 * @version 19
 */
class ApiJournalLogHandler extends AbstractHandler implements DbConnectionAwareInterface
{
    use DbConnectionTrait;

    /**
     * @param array $record
     *
     * @author Seif <ben.s@mipih.fr>
     */
    protected function write(array $record)
    {
        if (!isset($record['formatted'])) {
            return; // on a pas subi un formatage au préalable
        }
        $formatted = $record['formatted'];

        $query = $this->getDbConnection()->createQueryBuilder()
            ->insert($this->getFormatter()->getDbTableName())
            ->values($formatted[Sc::PLACE_HOLDERS_KEY])
            ->setParameters($formatted[Sc::PARAMETERS_KEY]);
        $query->execute();
    }
}