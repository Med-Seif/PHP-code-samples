<?php

namespace Gta\TracabiliteBundle\Log\Handler;

use Gta\CoreBundle\DataBase\DbConnectionTrait;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Class AbstractHandler
 *
 * @package Gta\TracabiliteBundle\Log\Handler
 * @author  Seif <ben.s@mipih.fr>
 */
abstract class AbstractHandler extends AbstractProcessingHandler
{
    /**
     * Ajouter un ensemble de processors
     *
     * @param array $processors
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function pushProcessors(array $processors)
    {
        foreach ($processors as $processor) {
            $this->pushProcessor($processor);
        }
    }
}