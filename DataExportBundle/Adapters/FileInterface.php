<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 02/04/2019 09:17
 */

namespace Gta\DataExportBundle\Adapters;

/**
 * Interface FileInterface
 * @package Gta\DataExportBundle\Adapters
 * @author  Seif <ben.s@mipih.fr> (02/04/2019/ 09:18)
 * @version 19
 */
interface FileInterface
{
    /**
     * @param $filenameWithoutExt
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function setFilename($filenameWithoutExt);

    /**
     * @param $author
     * @param $filename
     *
     * @return mixed
     */
    public function setFileParams($author, $filename);

    /**
     * @param null $filename
     *
     * @return $this
     */
    public function writeToFile($filename = null);

    /**
     * @param $filename
     *
     * @return mixed
     */
    public function readFile($filename);

    /**
     * @param $format
     *
     * @return $this
     */
    public function setFileFormat($format);

    /**
     * @return string
     */
    public function getFileName();
}