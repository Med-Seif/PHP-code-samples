<?php

namespace Gta\DataExportBundle\Adapters;


use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Interface ExportAdapterInterface
 * @package Gta\DataExportBundle\Adapters
 * @author  Seif <ben.s@mipih.fr>
 */
interface ExportAdapterInterface extends StyleInterface, PrintInterface, FontInterface, FileInterface, CellBorderInterface, FreezeInterface
{
    const PAGE_SIZE_A4          = 9;
    const FORMAT_PDF            = 'Pdf';
    const PAGE_SIZE_A3          = 8;
    const ORIENTATION_LANDSCAPE = 'landscape';
    const FORMAT_XLSX           = 'Xlsx';
    const ORIENTATION_PORTRAIT  = 'portrait';
    const ALIGN_CENTER          = 'center';
    const FORMAT_HTML           = 'Html';
    const FORMAT_CSV            = 'Csv';
    const ALIGN_TOP             = 'top';
    const ALIGN_RIGHT           = 'right';
    const ALIGN_BOTTOM          = 'bottom';
    const ALIGN_LEFT            = 'left';

    /**
     * @return int
     * @author Seif <ben.s@mipih.fr>
     */
    public function getLastRow();

    /**
     * @return int
     * @author Seif <ben.s@mipih.fr>
     */
    public function getLastCol();

    /**
     * @param $rowNumber
     * @param $colNumber
     * @param $data
     * @param $style
     *
     * @return $this
     */
    public function writeString($rowNumber, $colNumber, $data, $style = null);

    /**
     * @param $colIndex
     * @param $width
     *
     * @return $this
     */
    public function columnWidth($colIndex, $width);

    /**
     * @param $width
     *
     * @return mixed
     */
    public function setDefaultWidth($width);


    /**
     * @param $rowIndex
     * @param $height
     *
     * @return $this
     */
    public function rowHeight($rowIndex, $height);

    /**
     * @param $height
     *
     * @return mixed
     */
    public function setDefaultHeight($height);

    /**
     * @param $val
     *
     * @return $this
     */
    public function centerHorizontal($val);

    /**
     * @param $val
     *
     * @return $this
     */
    public function alignHorizontal($val = Alignment::HORIZONTAL_LEFT);

    /**
     * @param $val
     *
     * @return $this
     */
    public function alignVertical($val);

    /**
     * @return $this
     */
    public function alignWraptext();

    /**
     * @param null $id
     * @param null $title
     *
     * @return $this
     */
    public function addSheet($id = null, $title = null);

    /**
     * @param string $title
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setWorkSheetTitle($title);

    /**
     * Merge cells with their start indexes and their number
     *
     * @param     $row
     * @param     $col
     * @param int $width
     * @param int $height
     *
     * @return $this
     */
    public function cellMerge($row, $col, $width = 0, $height = 0);

    /**
     * Merge a range of cells
     *
     * @param integer $startRow
     * @param integer $startCol
     * @param integer $endRow
     * @param integer $endCol
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function mergeCellsRange($startRow, $startCol, $endRow, $endCol);

    /**
     * @param array $data
     * @param int   $startCol
     * @param int   $startRow
     *
     * @return $this
     */
    public function fromArray(array $data, $startRow = 1, $startCol = 1);

    /**
     * @param bool $flag
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setAutoSize($flag = true);

    /**
     * @param      $index
     * @param bool $flag
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function setColumnAutosize($index, $flag = true);

    /**
     * @param $columnIndex
     * @param $value
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyFilter($columnIndex, $value);

    /**
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function getCurrentWorkSheet();

    /**
     * @param $index
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setCurrentWorkSheet($index);

    /**
     * @return integer
     * @author Seif <ben.s@mipih.fr>
     */
    public function getCurrentWorkSheetIndex();

    /**
     * Adds filtering functionnality to the whole spreadsheet
     *
     * @param int $rowIndex Filtered row index
     * @param int $colIndex
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @author Seif <ben.s@mipih.fr>
     */
    public function enableAbsoluteColumnFiltering($rowIndex = 1, $colIndex = 1);

    /**
     * Adds filtering functionnality to a zone selectively
     *
     * @param $rowStart
     * @param $colStart
     * @param $rowEnd
     * @param $colEnd
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function enableZonalColumnFiltering($rowStart, $colStart, $rowEnd, $colEnd);

    /**
     * Sets the sheet selected by default when opening the workbook
     *
     * @param int $index
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setActiveSheet($index = 0);

    /**
     * Appliquer une formule sur une colonne
     *
     * @param $colIndex
     * @param $rowStart
     * @param $rowEnd
     * @param $formula
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyColFormula($colIndex, $rowStart, $rowEnd, $formula);

    /**
     * @param $val
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setScale($val);
}
