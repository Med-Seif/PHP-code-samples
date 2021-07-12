<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 11/02/2019 18:02
 */

namespace Gta\DataExportBundle\Exception;

/**
 * Class MissingFileException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (11/02/2019/ 20:14)
 * @version 19
 */
class MissingFileException extends DataExportException
{
    /**
     * MissingFileException constructor.
     *
     * @param string          $file
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($file, $message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct(parent::getFileErrorMessage($file), $code, $previous);
    }
}