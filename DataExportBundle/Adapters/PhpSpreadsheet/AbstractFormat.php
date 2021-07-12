<?php
/**
 * Created by PhpStorm.
 * User: ben.s
 * Date: 01/06/2018
 * Time: 17:09
 */

namespace Gta\DataExportBundle\Adapters\PhpSpreadsheet;

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class AbstractFormat
 *
 * @package Gta\DataExportBundle\Adapters\PhpSpreadsheet
 * @author  Seif <ben.s@mipih.fr>
 */
abstract class AbstractFormat
{
    /**
     * @return mixed
     */
    abstract public function getContentTypeHeader();

    /**
     * @return mixed
     */
    abstract public function getFileExtension();

    /**
     * @return mixed
     */
    abstract public function getID();

    /**
     * @param $spreadsheet
     *
     * @return \PhpOffice\PhpSpreadsheet\Writer\IWriter
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    final public function getWriterInstance($spreadsheet)
    {
        return IOFactory::createWriter($spreadsheet, $this->getID());
    }

}