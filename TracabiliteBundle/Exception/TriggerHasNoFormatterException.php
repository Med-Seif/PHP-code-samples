<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 14/05/2019 09:26
 */

namespace Gta\TracabiliteBundle\Exception;

use Throwable;

/**
 * Class TriggerHasNoFormatterException
 *
 * @package Gta\TracabiliteBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (14/05/2019/ 09:26)
 * @version 19
 */
class TriggerHasNoFormatterException extends TracabiliteException
{
    /**
     * TriggerHasNoFormatterException constructor.
     *
     * @param                 $trigger
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($trigger, $message = "", $code = 0, \Throwable $previous = null)
    {
        $message = '"' . strtoupper($trigger) . "\" has no matched formatter class name, You should define matching rules in supports method in the formatter class itself.";
        parent::__construct($message, $code, $previous);
    }
}