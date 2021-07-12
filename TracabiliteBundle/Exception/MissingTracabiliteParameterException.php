<?php

namespace Gta\TracabiliteBundle\Exception;

use Throwable;

/**
 * Class MissingTracabiliteParameterException
 *
 * @package Gta\TracabiliteBundle\Exception
 * @author  Seif <ben.s@mipih.fr>
 */
class MissingTracabiliteParameterException extends TracabiliteException
{
    const MSG_MISSING_PARAM = '"%key%" is missing';

    /**
     * MissingTracabiliteParameterException constructor.
     *
     * @param                 $key
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($key, $message = "", $code = 0, \Throwable $previous = null)
    {
        $message = str_replace('%key%', $key, self::MSG_MISSING_PARAM);
        parent::__construct($message, $code, $previous);
    }
}