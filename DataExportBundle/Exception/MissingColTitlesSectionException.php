<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 19/02/2019 18:26
 */

namespace Gta\DataExportBundle\Exception;

/**
 * Class MissingColTitlesSectionException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (19/02/2019/ 18:30)
 * @version 19
 */
class MissingColTitlesSectionException extends DataExportException
{
    /**
     * MissingColTitlesSectionException constructor.
     *
     * @param                 $colTitlesSectionID
     * @param                 $configFileName
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($colTitlesSectionID, $configFileName, $code = 0, \Throwable $previous = null)
    {
        $message = 'L\'export de cette liste n\' jamais été implémenté!';
        $message .= "\n You should define a section named \"$colTitlesSectionID\" in \" $configFileName \"";
        parent::__construct($message, $code, $previous);
    }
}