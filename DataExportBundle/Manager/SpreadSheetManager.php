<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 04/02/2020 on  14:52
 */

namespace Gta\DataExportBundle\Manager;

use Gta\CoreBundle\ParamConverter\MainFilter;
use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter;

/**
 * Spreadsheet is the file manager
 * It has a role to set the file name,
 * builds worksheet(s) and send the file to the output
 * Class SpreadSheetManager
 * @package Gta\DataExportBundle\Worksheet\Manager
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  18:42
 */
class SpreadSheetManager implements SpreadSheetInterface
{

    /** @var ExportAdapterInterface */
    public static $exportAdapter;

    /**
     * @var TsExportDirector
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 04/02/2020 on  17:45
     */
    private $director;
    /**
     * @var MainFilter
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 17/02/2020 on  20:25
     */
    private $mainFilter;


    /**
     * SpreadSheetManager constructor.
     *
     * @param PhpSpreadsheetAdapter $adapter
     * @param TsExportDirector      $director
     * @param MainFilter            $mainFilter
     */
    public function __construct(
        PhpSpreadsheetAdapter $adapter,
        TsExportDirector $director,
        MainFilter $mainFilter
    ) {
        self::$exportAdapter = $adapter;
        $this->director = $director;
        $this->mainFilter = $mainFilter;
    }

    /**
     * @param  $filename
     *
     * @return SpreadSheetInterface
     */
    final public function setName($filename): SpreadSheetInterface
    {
        $this->getAdapter()->setFilename($filename);

        return $this;
    }


    /**
     * @param array $data
     * @param int   $modelId
     *
     * @return SpreadSheetInterface
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 04/02/2020 on  17:49
     */
    public function buildWorkSheet(array $data, $modelId = 1): SpreadSheetInterface
    {
        $params = $this->mainFilter->toArray();
        $params['modelId'] = $modelId;
        // You can build multiple worksheets here !! (by service for example)
        $this->director->build($data, $params);

        return $this;
    }


    /**
     * @return SpreadSheetInterface
     */
    public function sendFile(): SpreadSheetInterface
    {
        $this->getAdapter()->writeToFile("php://output");

        return $this;
    }

    /**
     * @param $filename
     *
     * @return \Gta\DataExportBundle\Manager\SpreadSheetManager
     * @author Seif <ben.s@mipih.fr>
     */
    public function writeFile($filename): string
    {
        $this->getAdapter()->setFilename($filename);
        $this->getAdapter()->writeToFile();

        return $this->getAdapter()->getFileName();
    }

    /**
     * @return ExportAdapterInterface
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 30/01/2020 on  17:45
     */
    public function getAdapter()
    {
        return self::$exportAdapter;
    }
}