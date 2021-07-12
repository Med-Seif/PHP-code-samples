<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 10/03/2020 10:11
 */

namespace Gta\DataExportBundle\Planning\DataWriter\Body;

use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;

/**
 * Class AbstractRowBodyWriter
 *
 * @package Gta\DataExportBundle\Planning\DataWriter\Body
 * @author  Seif <ben.s@mipih.fr> (10/03/2020/ 10:11)
 * @version 19
 */
abstract class AbstractRowBodyWriter implements BodyWriterInterface
{
    use DefaultAdapterTrait;

    /**
     * @param array  $data
     * @param string $matric
     * @param string $dateff
     * @param string $horaire
     *
     * @return mixed
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    protected function getDataActivity(array $data, $matric, $dateff, $horaire)
    {
        $bodyKey = $matric.'_'.$dateff.'_'.$horaire;
        if (!array_key_exists($bodyKey, $data)) {
            return null;
        }

        return $data[$bodyKey];
    }
}