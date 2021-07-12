<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 11/02/2019 16:15
 */

namespace Gta\DataExportBundle\Exception;

use Gta\CoreBundle\Exception\GtaException;

/**
 * Class AbstractDataExportException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (11/02/2019/ 20:14)
 * @version 19
 */
class DataExportException extends GtaException
{
    /**
     * @param string $file
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    protected function getFileErrorMessage($file)
    {
        return "Fichier ".basename($file)." introuvable dans ".dirname($file);
    }
}