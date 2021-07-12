<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 02/04/2019 09:24
 */

namespace Gta\DataExportBundle\Adapters;

/**
 * Interface FreezeInterface
 * @package Gta\DataExportBundle\Adapters\PhpSpreadsheet
 * @author  Seif <ben.s@mipih.fr> (02/04/2019/ 09:24)
 * @version 19
 */
interface FreezeInterface
{
    /**
     * @param int $count
     *
     * @return mixed
     */
    public function freezeCols(int $count);

    /**
     * @param int $count
     *
     * @return mixed
     */
    public function freezeRows(int $count);

    /**
     * @param int $colCountFromLeft
     * @param int $rowCountFromTop
     */
    public function freezePane(int $colCountFromLeft, int $rowCountFromTop);
}