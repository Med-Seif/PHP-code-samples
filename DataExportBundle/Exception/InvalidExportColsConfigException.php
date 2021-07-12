<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 06/06/2019 11:30
 */

namespace Gta\DataExportBundle\Exception;

/**
 * Class InvalidExportColsConfigException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (06/06/2019/ 11:30)
 * @version 19
 */
class InvalidExportColsConfigException extends DataExportException
{
    const PROVIDER_KEY = 'provider';
    const ALIAS_KEY    = 'alias';

    /**
     * InvalidExportColsConfigException constructor.
     *
     * @param                 $key
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($key, $message = "", $code = 0, \Throwable $previous = null)
    {
        $message = $message."\n Config key: ".$key;
        parent::__construct($message, $code, $previous);
    }
}