<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 02/04/2019 09:12
 */

namespace Gta\DataExportBundle\Adapters;

/**
 * Interface StyleInterface
 * @package Gta\DataExportBundle\Adapters
 * @author  Seif <ben.s@mipih.fr> (02/04/2019/ 09:12)
 * @version 19
 */
interface StyleInterface
{
    /**
     * @return $this
     */
    public function createNewStyleObject();

    /**
     * Applies style on a CELL
     * Doesn't override applied style, it APPENDS
     *
     * @param       $rowNumber
     * @param       $colNumber
     * @param array $style
     *
     * @return mixed
     */
    public function applyStyle($rowNumber, $colNumber, array $style);

    /**
     * @return array
     */
    public function getStyleObject();

    /**
     * Applies style on a RANGE
     *
     * @param array   $style
     * @param integer $startRow
     * @param integer $startCol
     * @param integer $endRow
     * @param integer $endCol
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr> (07/02/2019/ 19:18)
     */
    public function applyStyleRange(array $style, $startRow, $startCol, $endRow, $endCol);

    /**
     * @param array $style
     * @param       $colIndex
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyStyleColumn(array $style, $colIndex);
    /**
     * @param array $style
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function setDefaultStyle(array $style);
}