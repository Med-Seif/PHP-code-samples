<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 18/02/2020 14:14
 */

namespace Gta\DataExportBundle\Exception;

/**
 * Class MissingFileNameConfigException
 *
 * @package Gta\DataExportBundle\Exception
 * @author  Seif <ben.s@mipih.fr> (18/02/2020/ 14:14)
 * @version 19
 */
class MissingFileNameConfigException extends DataExportException
{
    /**
     * MissingFileNameConfigException constructor.
     *
     * @param array $currentUcConfig
     * @param array $globalConfig
     */
    public function __construct(array $currentUcConfig, array $globalConfig)
    {
        $_1 = var_export($globalConfig, true);
        $_2 = var_export($currentUcConfig, true);

        parent::__construct(
            'You must provide a valid fname config in global section on under current export section'."\n".$_1."\n".$_2,
            null,
            null
        );
    }
}