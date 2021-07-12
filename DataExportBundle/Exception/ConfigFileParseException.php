<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 21/02/2019 14:17
 */

namespace Gta\DataExportBundle\Exception;

/**
 * Class ConfigFileParseException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (21/02/2019/ 14:18)
 * @version 19
 */
class ConfigFileParseException extends DataExportException
{
    /**
     * ConfigFileParseException constructor.
     *
     * @param string          $file
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($file, $message = "", $code = 0, \Throwable $previous = null)
    {
        $message = $message."\n"."File : ".$file;
        parent::__construct($message, $code, $previous);
    }
}