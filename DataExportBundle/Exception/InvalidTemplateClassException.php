<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 19/02/2019 14:38
 */

namespace Gta\DataExportBundle\Exception;

use Gta\DataExportBundle\Template\AbstractTemplate;

/**
 * Class InvalidTemplateClassException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (19/02/2019/ 14:38)
 * @version 19
 */
class InvalidTemplateClassException extends DataExportException
{
    /**
     * InvalidTemplateClassException constructor.
     *
     * @param                 $className
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($className, $message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            $className.' is either missing or not inheriting from '.AbstractTemplate::class,
            $code,
            $previous
        );
    }
}