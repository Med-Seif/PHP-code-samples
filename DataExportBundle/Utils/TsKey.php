<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 14/02/2020 on  00:31
 */

namespace Gta\DataExportBundle\Utils;

/**
 * Class TsKey
 * Grouping config keys
 *
 * @package Gta\DataExportBundle\Utils
 * @version 19
 */
class TsKey
{
    const K_HEADER                     = 'header';
    const K_BODY                       = 'body';
    const K_LEFT_SIDE                  = 'leftside';
    const K_STYLE                      = 'style';
    const K_ROW_START                  = 'rowStart';
    const K_COL_START                  = 'colStart';
    const K_ROWS                       = 'rows';
    const K_HEIGHT                     = 'height';
    const K_WIDTH                      = 'width';
    const K_FIRST_COL_INDEX            = 'firstColIndex';
    const K_FIRST_COL_WIDTH            = 'firstColWidth';
    const K_PRINT_CALLS                = 'calls';
    const K_PRINT_OPTIONS              = 'print_options';
    const K_PRINT_ALL_HEADER           = 'allHeader';
    const K_PRINT_ALL_FOOTER           = 'allFooter';
    const K_PRINT_METHOD_CALLS         = 'print_method_calls';
    const K_PRINT_PAPER_SIZE_INDEX     = 'paperSizeIndex';
    const K_PRINT_A3_NMB_COLS_PER_PAGE = 'a3_nmb_cols_per_page';
    const K_PRINT_A4_NMB_COLS_PER_PAGE = 'a4_nmb_cols_per_page';
    const K_OPTIONS                    = 'options';
    const K_PRINT_ORIENTATION          = 'orientation';
    const K_MODELE_ID                  = 'modelId';
    const K_CELL_SHOW_ACTIVITE         = 'cell_show_activite';
    const K_CELL_SHOW_REMUNERATION     = 'cell_show_remuneration';
    const K_CELL_SHOW_INDICATEURS      = 'cell_show_indicateurs';
    const K_CELL_SHOW_COUVERTURE       = 'cell_show_couverture';
    const K_FREEZE                     = 'freeze';
}