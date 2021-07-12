<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 11/06/2019 10:44
 */

namespace Gta\DataExportBundle\Template\Helper;

/**
 * Class DimensionHelper
 *
 * @package Gta\DataExportBundle\Template\Helper
 * @author  Seif <ben.s@mipih.fr> (11/06/2019/ 10:48)
 * @version 19
 */
class DimensionHelper
{
    /**
     * @var int nombre de lignes dans le header des titres
     */
    private $headerHeight = 1;
    /**
     * @var int largeur de l'entête
     */
    private $headerLength;
    /**
     * @var int ligne de début du header
     */
    private $headerRowIndexStart;
    /**
     * @var int ligne de fin du header
     */
    private $headerRowIndexEnd;
    /**
     * @var int index de la première ligne d'écriture
     */
    private $startRowIndex = 1;
    /**
     * @var int index de le première colonne d'écriture
     */
    private $startColIndex = 1;
    /**
     * @var int nombre de lignes dans le body
     */
    private $bodyHeight;
    /**
     * @var int index de la première ligne du body du tableau
     */
    private $bodyRowIndexStart;
    /**
     * @var int index de la dernière ligne du body
     */
    private $bodyRowIndexEnd;
    /**
     * @var int index de la ligne de début du footer
     */
    private $footerRowIndexStart;
    /**
     * @var int int nombre de lignes dans le footer
     */
    private $footerHeight = 1;
    /**
     * @var int index de la dernière ligne du footer
     */
    private $footerRowIndexEnd;
    /**
     * @var int index de la dernière ligne de tableau
     */
    private $endRowIndex;
    /**
     * @var int index de la dernière colonne du tableau
     */
    private $endColIndex;

    /**
     * DimensionHelper constructor.
     *
     * @param array $data
     * @param array $titles
     */
    public function __construct(array $data, array $titles)
    {
        $this->bodyHeight = count($data);
        $this->headerLength = count($titles);
        $this->calculate();
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public function calculate()
    {
        // header
        $this->headerRowIndexStart = $this->startRowIndex;
        $this->headerRowIndexEnd = $this->headerRowIndexStart + $this->headerHeight - 1;
        // body
        $this->bodyRowIndexStart = $this->startRowIndex + $this->headerHeight;
        $this->bodyRowIndexEnd = $this->bodyRowIndexStart + $this->bodyHeight - 1;

        // footer
        $this->footerRowIndexStart = $this->bodyRowIndexEnd + 1;
        $this->footerRowIndexEnd = $this->footerRowIndexStart + $this->footerHeight - 1;

        // dernière ligne et dernière colonne
        $this->endRowIndex = $this->footerRowIndexStart + $this->footerHeight - 1;
        $this->endColIndex = $this->startColIndex + $this->headerLength - 1;
    }

    public function debug()
    {
        var_dump(get_object_vars($this));
    }

    /**
     * @return int
     */
    public function getHeaderHeight()
    {
        return $this->headerHeight;
    }

    /**
     * @param int $headerHeight
     *
     * @return DimensionHelper
     */
    public function setHeaderHeight($headerHeight)
    {
        $this->headerHeight = $headerHeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getStartRowIndex()
    {
        return $this->startRowIndex;
    }

    /**
     * @param int $startRowIndex
     *
     * @return DimensionHelper
     */
    public function setStartRowIndex($startRowIndex)
    {
        $this->startRowIndex = $startRowIndex;

        return $this;
    }

    /**
     * @return int
     */
    public function getBodyRowIndexStart()
    {
        return $this->bodyRowIndexStart;
    }

    /**
     * @return mixed
     */
    public function getBodyHeight()
    {
        return $this->bodyHeight;
    }

    /**
     * @return mixed
     */
    public function getBodyRowIndexEnd()
    {
        return $this->bodyRowIndexEnd;
    }

    /**
     * @return mixed
     */
    public function getFooterRowIndexStart()
    {
        return $this->footerRowIndexStart;
    }

    /**
     * @param int $footerHeight
     *
     * @return DimensionHelper
     */
    public function setFooterHeight($footerHeight)
    {
        $this->footerHeight = $footerHeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getStartColIndex()
    {
        return $this->startColIndex;
    }

    /**
     * @param int $startColIndex
     *
     * @return DimensionHelper
     */
    public function setStartColIndex($startColIndex)
    {
        $this->startColIndex = $startColIndex;

        return $this;
    }

    /**
     * @return int
     * @author Seif <ben.s@mipih.fr>
     */
    public function getEndColIndex()
    {
        return $this->getStartColIndex() + $this->getHeaderLength() - 1;
    }

    /**
     * @return int
     */
    public function getHeaderLength()
    {
        return $this->headerLength;
    }

    /**
     * @return int
     */
    public function getFooterRowIndexEnd()
    {
        return $this->footerRowIndexEnd;
    }

    /**
     * @return int
     */
    public function getHeaderRowIndexStart()
    {
        return $this->headerRowIndexStart;
    }

    /**
     * @return int
     */
    public function getHeaderRowIndexEnd()
    {
        return $this->headerRowIndexEnd;
    }

}