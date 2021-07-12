<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 02/04/2019 09:14
 */

namespace Gta\DataExportBundle\Adapters;

/**
 * Interface PrintInterface
 * @package Gta\DataExportBundle\Adapters
 * @author  Seif <ben.s@mipih.fr> (02/04/2019/ 09:20)
 * @version 19
 */
interface PrintInterface
{
    /**
     * @param $val
     *
     * @return $this
     */
    public function setMarginTop($val);

    /**
     * @param $val
     *
     * @return $this
     */
    public function setMarginBottom($val);

    /**
     * @param $val
     *
     * @return $this
     */
    public function setMarginLeft($val);

    /**
     * @param $val
     *
     * @return $this
     */
    public function setMarginRight($val);

    /**
     * @return $this
     */
    public function setLandscape();

    /**
     * @return $this
     */
    public function setPortrait();

    /**
     * @param $val
     *
     * @return mixed
     */
    public function setPrintScale($val);

    /**
     * @param $s
     * @param $f
     *
     * @return $this
     */
    public function setRepeatRow($s, $f);

    /**
     * @param $s
     * @param $f
     *
     * @return $this
     */
    public function setRepeatCol($s, $f);

    /**
     * @param $val
     *
     * @return $this
     */
    public function setPaperSizeIndex($val);

    /**
     * @param $position
     * @param $val
     *
     * @return $this
     */
    public function setHeader($position, $val);

    /**
     * @param $position
     * @param $val
     *
     * @return $this
     */
    public function setFooter($position, $val);

    /**
     * Définit le header en une seule fois en passant une chaine de caractères
     *
     * @param string $val
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setAllHeader($val);

    /**
     * Définit le footer en une seule fois en passant une chaine de caractères
     *
     * @param string $val
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setAllFooter($val);

    /**
     * Setting orientation shortcut instead of calling setLandscape or setPortrait
     *
     * @param $orientation
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setOrientation($orientation);

    /**
     * @param $val
     * @return $this
     */
    public function setFitHeight($val);

    /**
     * Fit to Width
     * @param null|Integer $val valeur
     * @return   $this
     * @author   Seif <ben.s@mipih.fr>
     */
    public function setFitWidth($val);

    /**
     * Print a row break on printing and pass to next page
     *
     * @param $rowIndex
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function setBreakRow($rowIndex);

    /**
     * Print a col break on printing and pass to next page
     *
     * @param $colIndex
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function setBreakCol($colIndex);
}