<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 26/02/2020 14:18
 */

namespace Gta\DataExportBundle\Event\Listener;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface TsExportDataSourceResponseStrategyInterface
 * @package Gta\DataExportBundle\Event\Listener
 * @author  Seif <ben.s@mipih.fr> (26/02/2020/ 14:18)
 * @version 19
 */
interface TsExportDataSourceStrategyInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function loadData(Response $response = null): array;
}