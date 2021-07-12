<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 01/08/2019 10:11
 */

namespace Gta\TracabiliteBundle\Exception;

/**
 * Class MessageDesciptionGenerationException
 *
 * @package Gta\TracabiliteBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (01/08/2019/ 10:12)
 * @version 19
 */
class MessageDesciptionGenerationException extends TracabiliteException
{
    /**
     * MessageDesciptionGenerationException constructor.
     *
     * @param string                                          $message
     * @param                                                 $params
     * @param                                                 $template
     * @param int                                             $code
     * @param \Gta\TracabiliteBundle\Exception\Throwable|null $previous
     *
     * @throws \ReflectionException
     */
    public function __construct($message = "", $params, $template, $code = 0, Throwable $previous = null)
    {
        $strParams = ['vide'];
        if (is_array($params)) {
            $strParams = var_export($params, true);
        }
        $message .= <<<EOT


Params passed to evaluator :  = {$strParams}
Current template : $template
EOT;
        parent::__construct($message, $code, $previous);
    }
}