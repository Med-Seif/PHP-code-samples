<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 27/02/2020 10:01
 */

namespace Gta\DataExportBundle\Builder;


use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Utils\TsKey;

/**
 * Class GlobalStyleApplier
 *
 * @package Gta\DataExportBundle\Builder
 * @author  Seif <ben.s@mipih.fr> (27/02/2020/ 10:08)
 * @version 19
 */
class GlobalStyleApplier
{
    use ModelSetterTrait;
    /**
     * @var \Gta\DataExportBundle\Adapters\ExportAdapterInterface
     */
    private $adapter;

    /**
     * GlobalStyleApplier constructor.
     *
     * @param \Gta\DataExportBundle\Adapters\ExportAdapterInterface $adapter
     */
    public function __construct(ExportAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function apply()
    {
        $model = $this->getModel();
        // Init options
        $countCol = $model->getCountCol();
        $countRow = $model->getCountRow();
        $header = YmlConfigArrayAccess::getConfig('header');
        $body = YmlConfigArrayAccess::getConfig('body');
        $leftSide = YmlConfigArrayAccess::getConfig('left');
        # Global style application
        $this->applyGlobalStyle($header, $leftSide, $countRow, $countCol);
        $this->applyHeaderStyle($header, $countCol);
        $this->applyLeftSideStyle($leftSide, $countRow);
        $this->applyBodyStyle($body, $countCol, $countRow);
        $this->freeze($header, $body); # grrrrr it's cold
        $this->applyGlobalGridStyle();
        $this->applySpecificStyle();
    }

    /**
     * @param $header
     * @param $countCol
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyHeaderStyle($header, $countCol)
    {
        $this->adapter->applyStyleRange(
            $header[TsKey::K_STYLE],
            $header[TsKey::K_ROW_START],
            $header[TsKey::K_COL_START],
            $header[TsKey::K_ROWS],
            $countCol
        );

        $this->adapter->rowHeight($header[TsKey::K_ROW_START], $header[TsKey::K_HEIGHT]);
        # only for models with more than one row in header
        if ($header[TsKey::K_ROWS] > 1) {
            $this->adapter->rowHeight($header[TsKey::K_ROWS], $header[TsKey::K_HEIGHT]);
        }
        $this->adapter->columnWidth($header[TsKey::K_FIRST_COL_INDEX], $header[Tskey::K_FIRST_COL_WIDTH]);
        $this->adapter->setDefaultWidth($header[TsKey::K_WIDTH]);
    }

    /**
     * @param $leftSide
     * @param $countRow
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyLeftSideStyle($leftSide, $countRow)
    {
        $this->adapter->applyStyleRange(
            $leftSide[TsKey::K_STYLE],
            $leftSide[TsKey::K_ROW_START],
            $leftSide[TsKey::K_COL_START],
            $countRow,
            $leftSide[TsKey::K_COL_START]
        );

        $this->adapter->columnWidth($leftSide[TsKey::K_COL_START], $leftSide['width']);
    }

    /**
     * @param $body
     * @param $countCol
     * @param $countRow
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyBodyStyle($body, $countCol, $countRow)
    {
        $this->adapter->applyStyleRange(
            $body[TsKey::K_STYLE],
            $body[TsKey::K_ROW_START],
            $body[TsKey::K_COL_START],
            $countRow,
            $countCol
        );
    }

    /**
     * @param $header
     * @param $body
     *
     * @author Seif <ben.s@mipih.fr>
     */
    private function freeze($header, $body)
    {
        if (!empty($body[TsKey::K_FREEZE]) && !empty($header[TsKey::K_FREEZE])) {
            $this->adapter->freezePane($body[TsKey::K_FREEZE], $header[TsKey::K_FREEZE]);
        }
    }

    /**
     * @param $header
     * @param $leftSide
     * @param $countRow
     * @param $countCol
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyGlobalStyle($header, $leftSide, $countRow, $countCol)
    {
        // global worksheet style (borders, ...)
        $globalStyle = $this->adapter
            ->createNewStyleObject()
            ->border('all', 1, 'AEAEAE')
            ->getStyleObject();

        $this->adapter->applyStyleRange(
            $globalStyle,
            $header[TsKey::K_ROW_START],
            $leftSide[TsKey::K_COL_START],
            $countRow,
            $countCol
        );
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyGlobalGridStyle()
    {
        $this->model->applyGlobalGridStyle();
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    private function applySpecificStyle()
    {
        $this->model->applySpecificStyle();
    }
}