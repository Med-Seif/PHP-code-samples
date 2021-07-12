<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 10/04/2019 20:38
 */

namespace Gta\DataExportBundle\Exception;

/**
 * Class InvalidExportTitleException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (06/06/2019/ 11:30)
 * @version 19
 */
class InvalidExportTitleException extends DataExportException
{
    /**
     * InvalidExportTitleException constructor.
     *
     * @param                 $colTitlesSectionID
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($colTitlesSectionID, $message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            'You should define a title under "'.$colTitlesSectionID. '" section in the export config file',
            $code,
            $previous
        );
    }
}