<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 24/04/2019 11:07
 */

namespace Gta\DataExportBundle\Exception;

/**
 * Class MissingColNamesException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (24/04/2019/ 11:07)
 * @version 19
 */
class MissingColNamesException extends DataExportException
{
    /**
     * MissingColNamesException constructor.
     *
     * @param                 $colTitlesSectionID
     * @param                 $configFileName
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        $colTitlesSectionID,
        $configFileName,
        $message = "",
        $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct(
            "You must define at least one column name under \"$colTitlesSectionID\" section located in the file : \" $configFileName \"",
            $code,
            $previous
        );
    }
}