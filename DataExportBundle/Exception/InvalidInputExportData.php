<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 07/05/2019 12:48
 */

namespace Gta\DataExportBundle\Exception;

/**
 * Class InvalidInputExportData
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (07/05/2019/ 12:59)
 * @version 19
 */
class InvalidInputExportData extends DataExportException
{
    /**
     * InvalidInputExportData constructor.
     *
     * @param                 $dataKeys
     */
    public function __construct($dataKeys)
    {
        parent::__construct(
            'You should provide a numeric array, keys that were provided are :  ['.implode(', ', $dataKeys).']'
        );
    }
}