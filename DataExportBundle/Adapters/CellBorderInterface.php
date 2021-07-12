<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 02/04/2019 09:22
 */

namespace Gta\DataExportBundle\Adapters;

/**
 * Interface CellBorderInterface
 * @package Gta\DataExportBundle\Adapters
 * @author  Seif <ben.s@mipih.fr> (02/04/2019/ 09:22)
 * @version 19
 */
interface CellBorderInterface
{
    /**
     * @param $position
     * @param $width
     * @param $color
     *
     * @return mixed
     */
    public function border($position, $width, $color);
    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderAll($width, $color);
    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderRight($width, $color);

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     */
    public function borderTop($width, $color);

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     */
    public function borderLeft($width, $color);

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderBottom($width, $color);

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderVertical($width, $color);

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderHorizontal($width, $color);

}