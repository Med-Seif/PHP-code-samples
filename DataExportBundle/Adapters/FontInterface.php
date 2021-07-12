<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 02/04/2019 09:16
 */

namespace Gta\DataExportBundle\Adapters;

/**
 * Interface FontInterface
 * @package Gta\DataExportBundle\Adapters
 * @author  Seif <ben.s@mipih.fr> (02/04/2019/ 09:16)
 * @version 19
 */
interface FontInterface
{
    /**
     * @param $val
     *
     * @return $this
     */
    public function fontColor($val);

    /**
     * @param $val
     *
     * @return $this
     */
    public function bgColor($val);

    /**
     * @param $val
     *
     * @return $this
     */
    public function fontSize($val);

    /**
     * @return $this
     */
    public function fontBold();

    /**
     * @return $this
     */
    public function fontItalic();

    /**
     * Description
     *
     * @return $this
     */
    public function fontUnderline();
}