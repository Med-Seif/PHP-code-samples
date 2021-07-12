<?php

namespace Gta\DataExportBundle\Adapters\PhpSpreadsheet;

use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\Domain\Lib\Std;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class PhpSpreadsheetAdaptor
 * @link   https://phpspreadsheet.readthedocs.io/en/develop/topics/recipes
 *         https://github.com/PHPOffice/PhpSpreadsheet
 * @author Seif <ben.s@mipih.fr>
 */
class PhpSpreadsheetAdapter implements ExportAdapterInterface
{
    /**
     * Nom de la bibliothèque PDF utilisée
     */
    const PDF_LIB = 'Tcpdf';
    /**
     * Méthode de calcul de couleur (RGB ou ARGB)
     */
    const COLOR_METHOD = 'rgb';
    /**
     * Path par défaut ou enregistrer les fichiers d'export
     */
    const EXPORT_PATH = __DIR__.'/../../../../../var/tmp/';
    /**
     * PhpSpreadsheetAdapter constructor.
     *
     * @param string $format Format
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    const ALIGNMENT = 'alignment';
    /**
     * Number of maximum caracters allowed for a file name
     *
     * Une fois en Français, sometimes in english, I don't calcule
     */
    const MAX_CARACTERS_FILE_NAME_COUNT = 150;
    /**
     * PhpSpreadsheetAdapter constructor.
     *
     * @param string $format
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    const COLOR = 'color';
    /**
     * Objet document
     * @var Spreadsheet Spreadsheet
     */
    protected $spreadsheet;
    /**
     * Objet feuille ou page courante
     * @var \PhpOffice\PhpSpreadsheet\Writer\Xls\Worksheet
     */
    protected $currentWorksheet;
    /**
     * Nom par défaut du fichier
     * @var string
     */
    protected $filename;
    /**
     * Compteur interne des feuilles
     * @var integer
     */
    protected $internalSheetCounter = 0;
    /**
     * Objet de style
     * @var Mixed (Array | Object)
     */
    protected $styleObject;
    /**
     * String format de l'export
     */
    protected $fileFormat;

    /**
     * PhpSpreadsheetAdapter constructor.
     *
     * @param string $format
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function __construct($format = ExportAdapterInterface::FORMAT_XLSX)
    {
        $this->setFileFormat($format);
        $this->spreadsheet = new Spreadsheet();
        $this->currentWorksheet = $this->getSpreadsheet()->getActiveSheet();
    }

    /**
     * Ajouter une feuille au classeur actuel, et qui devient la feuille courante
     *
     * @param String $id Identifiant unique
     * @param null   $title
     *
     * @return int
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function addSheet($id = null, $title = null)
    {
        $sheetID = (!is_int($id) || is_null($id)) ? (++$this->internalSheetCounter) : $id;
        $title = (null === $title) ? strval($sheetID) : $title;
        $ws = new Worksheet(null, $title);
        $this->getSpreadsheet()->addSheet($ws, $sheetID);
        $this->setCurrentWorksheet($sheetID);

        return $sheetID;
    }

    /**
     * Écriture vers une cellule
     * L'attribution d'un style à une cellule se fait toujours par deux manières :
     *   1- Ws::getStyle($pCoordinate)::applyFromArray(Array $style);
     *   2- Ws::getStyle($pCoordinate)::getFont()::getColor()::setARGB()
     * Nous préviligerions l'écriture avec la méthode 1 qui passe le tous en une seule instruction
     *
     * @param Integer $rowNumber coord X
     * @param Integer $colNumber coord Y
     * @param String  $data      Texte à écrire dans la cellule
     * @param Mixed   $style     Objet de style, peut être sous la forme d'un tableau
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeString($rowNumber, $colNumber, $data, $style = null)
    {
        if (null === $style) {
            $style = [];
        }
        $pCoordinate = $this->getCellRefFromCoords($rowNumber, $colNumber);
        $this->getCurrentWorkSheet()->setCellValue($pCoordinate, $data);
        $this->getCurrentWorkSheet()
            ->getStyle($pCoordinate)
            // La méthode applyFromArray est bien conseillée pour des raisons de performance
            ->applyFromArray($style);

        return $this;
    }

    /**
     * Modifie la largeur d'une colonne
     *
     * @param Integer $colIndex Index de la colonne (allant de 1)
     * @param Integer $width    Valeur de la largeur
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function columnWidth($colIndex, $width)
    {
        if (is_int($colIndex)) {
            $colIndex = Coordinate::stringFromColumnIndex($colIndex);
        }
        $this->getCurrentWorkSheet()
            ->getColumnDimension($colIndex)
            ->setWidth($width);

        return $this;
    }

    /**
     * Modifie la hauteur de la ligne
     *
     * @param Integer $rowIndex Index de la ligne (allant de 1)
     * @param Integer $height   Valeur de la hauteur
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function rowHeight($rowIndex, $height)
    {
        // le row est toujours numérique, pas besoin de convertir
        $this->getCurrentWorkSheet()
            ->getRowDimension($rowIndex)
            ->setRowHeight($height);

        return $this;
    }

    /**
     * Fusion de cellules
     *
     * @param int $row    Ligne de début
     * @param int $col    Colonne de début
     * @param int $width  Nombre de cases de gauche à droite
     * @param int $height Nombre de cases en haut en bas
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function cellMerge($row, $col, $width = 0, $height = 0)
    {
        $this->getCurrentWorkSheet()->mergeCellsByColumnAndRow(
            $row,
            $col,
            $row + ($width - 1),
            $col + ($height - 1)
        );

        return $this;
    }

    /**
     * Alimenter une page à partir d'un tableau multidimentionnel
     *
     * @param array $data
     * @param int   $startRow
     * @param int   $startCol
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @see https://phpspreadsheet.readthedocs.io/en/develop/topics/accessing-cells/#setting-a-range-of-cells-from-an-array
     */
    public function fromArray(array $data, $startRow = 1, $startCol = 1)
    {
        $pCoordinate = $this->getCellRefFromCoords($startRow, $startCol);
        $this->getCurrentWorkSheet()->fromArray($data, null, $pCoordinate, false);

        return $this;
    }

    /**
     * Alignement horizontal du contenu de la cellule
     *
     * @param String $val 'right', 'left' ou 'center'
     *
     * @return $this
     */
    public function alignHorizontal($val = Alignment::HORIZONTAL_LEFT)
    {
        if ($val === ExportAdapterInterface::ALIGN_RIGHT) {
            $val = Alignment::HORIZONTAL_RIGHT;
        } elseif ($val === ExportAdapterInterface::ALIGN_CENTER) {
            $val = Alignment::HORIZONTAL_CENTER;
        }
        $this->styleObject[self::ALIGNMENT]['horizontal'] = $val;

        return $this;
    }

    /**
     * @param String $val 'top', 'bottom' ou 'center'
     *
     * @return $this
     */
    public function alignVertical($val)
    {
        $alignment = Alignment::VERTICAL_BOTTOM;
        if ($val === ExportAdapterInterface::ALIGN_TOP) {
            $alignment = Alignment::VERTICAL_TOP;
        } elseif ($val === ExportAdapterInterface::ALIGN_CENTER) {
            $alignment = Alignment::VERTICAL_CENTER;
        }
        $this->styleObject[self::ALIGNMENT]['vertical'] = $alignment;

        return $this;
    }

    /**
     * @return $this
     */
    public function alignWraptext()
    {
        $this->styleObject[self::ALIGNMENT]['wrapText'] = true;

        return $this;
    }

    /**
     * @param array $style
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setDefaultStyle(array $style)
    {
        $this->getSpreadsheet()->getDefaultStyle()->applyFromArray($style);

        return $this;
    }

    /**
     * Écriture dans le fichier ExcelWriterXml
     *
     * @param null $filename
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @author  Seif <ben.s@mipih.fr>
     */
    public function writeToFile($filename = null)
    {
        // L'ID est identique au nom du format sauf pour le PDF il dépend de la librairie installée derrière
        $id = (ExportAdapterInterface::FORMAT_PDF === $this->fileFormat) ? self::PDF_LIB : $this->fileFormat;
        $writer = IOFactory::createWriter($this->spreadsheet, $id);
        if (ExportAdapterInterface::FORMAT_PDF === $this->fileFormat) {
            // cet appel est obligatoire pour le PDF, sinon on aura toujours
            // une seule page écrite, par contre il lance une exception pour l'excel
            $writer->writeAllSheets();
        }

        // Man! why are you upgrading this method
        //Because I need to send a temporary file as output
        $filenameOutput = $this->getFilename();
        if (null !== $filename && in_array(strtolower($filename), ['php://output', 'php://stdout'])) {
            $filenameOutput = $filename;
        }

        $writer->save($filenameOutput);

        return $this;
    }

    /**
     * Téléchargement depuis le navigateur
     *
     * @param string $filename Nom fichier
     *
     * @return void
     * @author  Seif <ben.s@mipih.fr>
     */
    public function readFile($filename)
    {
        readfile($filename);
        unlink($filename);
    }

    /**
     * @return int
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function getLastRow()
    {
        return intval($this->getCurrentWorkSheet()->getHighestRow());
    }

    /**
     * @return int
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function getLastCol()
    {
        $lastCol = $this->getCurrentWorkSheet()->getHighestColumn();

        return Coordinate::columnIndexFromString($lastCol);
    }

    /**
     * @param bool $flag
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setAutoSize($flag = true)
    {
        $range = range(1, $this->getLastCol());
        foreach ($range as $column) {
            $this
                ->getCurrentWorkSheet()
                ->getColumnDimension(Coordinate::stringFromColumnIndex($column))
                ->setAutoSize($flag);
        }

        return $this;
    }

    /**
     * @param int $rowIndex cell start index row
     * @param int $colIndex cell start index col
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function enableAbsoluteColumnFiltering($rowIndex = 1, $colIndex = 1)
    {
        if (1 === $rowIndex && 1 === $colIndex) {
            $range = $this
                ->getSpreadsheet()
                ->getActiveSheet()
                ->calculateWorksheetDimension();

        } else {
            $range = $this->getCellRefFromCoords($rowIndex, $colIndex)
                .':'
                .$this->getCurrentWorkSheet()->getHighestColumn()
                .$this->getCurrentWorkSheet()->getHighestRow();

        }
        $this->getSpreadsheet()->getActiveSheet()->setAutoFilter($range);

        return $this;
    }

    /**
     * @param $columnIndex
     * @param $value
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyFilter($columnIndex, $value)
    {
        if (null === $columnIndex && null === $value) {
            return $this;
        }
        $autoFilter = $this->getSpreadsheet()->getActiveSheet()->getAutoFilter();
        $columnFilter = $autoFilter->getColumn('A');
        $columnFilter->setFilterType(
            Column::AUTOFILTER_FILTERTYPE_FILTER
        );
        $columnFilter->createRule()
            ->setRule(
                Column\Rule::AUTOFILTER_COLUMN_RULE_EQUAL,
                $value
            );

        return $this;
    }

    /**
     * @param $position
     * @param $val
     *
     * @deprecated
     * @category Impression
     */
    public function setHeader($position, $val)
    {
        throw new \LogicException(
            'Method '.__FUNCTION__.' is deprecated for '.__CLASS__.', use "setAllHeader($val)" instead'
        );
    }

    /**
     * @param $position
     * @param $val
     *
     * @deprecated
     * @category Impression
     */
    public function setFooter($position, $val)
    {
        throw new \LogicException(
            'Method '.__FUNCTION__.' is deprecated for '.__CLASS__.', use "setAllFooter($val)" instead'
        );
    }

    /**
     * Définit le header en une seule fois en passant une chaine de caractères
     *
     * @param string $val
     *
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setAllHeader($val)
    {
        $this->getCurrentWorkSheet()
            ->getHeaderFooter()
            ->setOddHeader($val);

        return $this;
    }

    /**
     * Définit le footer en une seule fois en passant une chaine de caractères
     *
     * @param string $val
     *
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setAllFooter($val)
    {
        $this->getCurrentWorkSheet()
            ->getHeaderFooter()
            ->setOddFooter($val);

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setWorkSheetTitle($title)
    {
        $this->getCurrentWorkSheet()->setTitle($title);

        return $this;
    }

    /**
     * @return Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function getCurrentWorkSheet()
    {
        return $this->getSpreadsheet()->getActiveSheet();
    }

    /**
     * @param $index
     *
     * @return $this
     * @throws Exception
     * @author  Seif <ben.s@mipih.fr>
     */
    public function setCurrentWorksheet($index)
    {
        $this->getSpreadsheet()->setActiveSheetIndex($index);

        return $this;
    }

    /**
     * @return integer
     * @author Seif <ben.s@mipih.fr>
     */
    public function getCurrentWorkSheetIndex()
    {
        return $this->getSpreadsheet()->getActiveSheetIndex();
    }

    /**
     * Autosizer une seule colonne
     * NB!! une fois autosizé on peut plus manipuler la largeur d'une colonne
     *
     * @param integer $index
     * @param bool    $flag
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setColumnAutosize($index, $flag = true)
    {
        $this
            ->getCurrentWorkSheet()
            ->getColumnDimension(Coordinate::stringFromColumnIndex($index))
            ->setAutoSize($flag);

        return $this;

    }

    /**
     * @param         $position
     * @param Integer $width
     * @param         $color
     *
     * @return $this
     */
    public function border($position, $width, $color)
    {
        if ('all' === $position) {
            $position = 'allBorders';
        }
        if ($width > 0 && $width < 2) {
            $border = Border::BORDER_THIN;
        } elseif ($width >= 2 && $width < 4) {
            $border = Border::BORDER_MEDIUM;
        } else {
            $border = Border::BORDER_THICK;
        }
        $this->styleObject['borders'][$position] = [
            'borderStyle' => $border,
            self::COLOR   => [self::COLOR_METHOD => $color],
        ];

        return $this;
    }

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderAll($width, $color)
    {
        return $this->border('all', $width, $color);
    }

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderRight($width, $color)
    {
        return $this->border('right', $width, $color);
    }

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderTop($width, $color)
    {
        return $this->border('top', $width, $color);
    }

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderLeft($width, $color)
    {
        return $this->border('left', $width, $color);
    }

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderBottom($width, $color)
    {
        return $this->border('bottom', $width, $color);
    }

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderVertical($width, $color)
    {
        return $this->border('vertical', $width, $color);
    }

    /**
     * @param $width
     * @param $color
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function borderHorizontal($width, $color)
    {
        return $this->border('horizontal', $width, $color);
    }

    /**
     * Instancier un nouveau objet style
     * Pour cet adaptateur la classe de style n'a aucun sens
     * puisqu'elle est appelée et utilisée toujours en arrière plan.
     * L'attribution d'un style à une cellule se fait toujours par deux manières :
     * 1- Ws::getStyle($pCoordinate)::applyFromArray(Array $style);
     * 2- Ws::getStyle($pCoordinate)::getFont()::getColor()::setARGB()
     * @return $this
     */
    public function createNewStyleObject()
    {
        $this->styleObject = [];

        return $this;
    }

    /**
     * @param       $rowNumber
     * @param       $colNumber
     * @param array $style
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr> (07/02/2019/ 16:43)
     */
    public function applyStyle($rowNumber, $colNumber, array $style)
    {
        if (0 === count($style)) {
            return $this;
        }
        $pCoordinate = $this->getCellRefFromCoords($rowNumber, $colNumber);
        $this->getCurrentWorkSheet()
            ->getStyle($pCoordinate)
            ->applyFromArray($style);

        return $this;
    }

    /**
     * apply style on a range of cells that represents a column
     *
     * @param array $style
     * @param       $startRow
     * @param       $startCol
     * @param       $endRow
     * @param       $endCol
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr> (07/02/2019/ 19:18)
     */
    public function applyStyleRange(array $style, $startRow, $startCol, $endRow, $endCol)
    {
        $pCoordinateStart = $this->getCellRefFromCoords($startRow, $startCol);
        $pCoordinateFinish = $this->getCellRefFromCoords($endRow, $endCol);
        $this->getCurrentWorkSheet()->getStyle($pCoordinateStart.':'.$pCoordinateFinish)
            ->applyFromArray($style);
    }

    /**
     * {@inheritdoc}
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyStyleColumn(array $style, $colIndex)
    {
        $height = (integer)$this->getCurrentWorkSheet()->getHighestRow();

        $this->applyStyleRange(
            $style,
            1,
            $colIndex,
            $height,
            $colIndex
        );
    }

    /**
     * Getter styleObject
     * @return Mixed
     */
    public function getStyleObject()
    {
        return $this->styleObject;
    }

    /**
     * @param $styleObject
     *
     * @return $this
     */
    public function setStyleObject($styleObject)
    {
        $this->styleObject = $styleObject;

        return $this;
    }

    /**
     * Fixe un nombre de colonnes de gauche jusqu'a
     *
     * @param Integer $count
     *
     * @return void
     * @deprecated
     */
    public function freezeCols(int $count)
    {
        trigger_error(
            'This function is not available for this adapter type, use freezePane method instead',
            E_USER_ERROR
        );
    }

    /**
     * Fixe un nombre de lignes de haut à partir de la première
     *
     * @param Integer $count
     *
     * @return $this
     * @deprecated
     */
    public function freezeRows(int $count)
    {
        trigger_error('This function is available for this adapter type, use freezePane method instead', E_USER_ERROR);

        return $this;
    }

    /**
     * @param $val
     *
     * @return $this
     */
    public function bgColor($val)
    {
        $this->styleObject['fill'][self::COLOR][self::COLOR_METHOD] = $val;
        $this->styleObject['fill']['fillType'] = Fill::FILL_SOLID;

        return $this;
    }

    /**
     * @param $val
     *
     * @return $this
     */
    public function fontColor($val)
    {
        $this->styleObject['font'][self::COLOR][self::COLOR_METHOD] = $val;

        return $this;
    }

    /**
     * Définit les espaces d'impression
     *
     * @param Integer $val Nouvelle valeur
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function setMarginTop($val)
    {
        $this->getCurrentWorkSheet()
            ->getPageMargins()
            ->setTop($val);

        return $this;
    }

    /**
     * Définit les espaces d'impression
     *
     * @param Integer $val Nouvelle valeur
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function setMarginBottom($val)
    {
        $this->getCurrentWorkSheet()
            ->getPageMargins()
            ->setBottom($val);

        return $this;
    }

    /**
     * Définit les espaces d'impression
     *
     * @param Integer $val Nouvelle valeur
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function setMarginLeft($val)
    {
        $this->getCurrentWorkSheet()
            ->getPageMargins()
            ->setLeft($val);

        return $this;
    }

    /**
     * Définit les espaces d'impression
     *
     * @param Integer $val Nouvelle valeur
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function setMarginRight($val)
    {
        $this->getCurrentWorkSheet()
            ->getPageMargins()
            ->setRight($val);

        return $this;
    }

    /**
     * Disposition de la page
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function setLandscape()
    {
        $this->getCurrentWorkSheet()
            ->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        return $this;
    }

    /**
     * Disposition de la page
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function setPortrait()
    {
        $this->getCurrentWorkSheet()
            ->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_PORTRAIT);

        return $this;
    }

    /**
     * Centrage horizontale
     *
     * @param Mixed $val Flag qui sera convertit en boolean
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function centerHorizontal($val)
    {
        $this->getCurrentWorkSheet()
            ->getPageSetup()
            ->setHorizontalCentered(
                boolval($val)
            );

        return $this;
    }

    /**
     * Définir le zoom de l'impression
     *
     * @param Float $val Niveau de zoom à l'impression
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setScale($val)
    {
        $this->getCurrentWorkSheet()
            ->getSheetView()
            ->setZoomScale($val);

        return $this;
    }

    /**
     * @param $val
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setPrintScale($val)
    {
        $this->getCurrentWorkSheet()
            ->getPageSetup()
            ->setScale($val);

        return $this;
    }

    /**
     * Ligne qui se répéte sur chaque page d'impression
     *
     * @param Integer $s Start
     * @param Integer $f Finish
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function setRepeatRow($s, $f)
    {
        $this->getCurrentWorkSheet()
            ->getPageSetup()
            ->setRowsToRepeatAtTopByStartAndEnd($s, $f);

        return $this;
    }

    /**
     * Colonne qui se répéte sur chaque page d'impression
     *
     * @param Integer $s Start
     * @param Integer $f Finish
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     */
    public function setRepeatCol($s, $f)
    {
        $s_ = Coordinate::stringFromColumnIndex($s);
        $f_ = Coordinate::stringFromColumnIndex($f);
        $this->getCurrentWorkSheet()
            ->getPageSetup()
            ->setColumnsToRepeatAtLeftByStartAndEnd($s_, $f_);

        return $this;
    }

    /**
     * Fit to Height
     *
     * @param null|Integer $val valeur
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     * @author   Seif <ben.s@mipih.fr>
     */
    public function setFitHeight($val)
    {
        $this->getCurrentWorkSheet()
            ->getPageSetup()
            ->setFitToHeight($val);

        return $this;
    }

    /**
     * Fit to Width
     *
     * @param null|Integer $val valeur
     *
     * @return   $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @category Impression
     * @author   Seif <ben.s@mipih.fr>
     */
    public function setFitWidth($val)
    {
        $this->getCurrentWorkSheet()
            ->getPageSetup()
            ->setFitToWidth($val);

        return $this;
    }

    /**
     * @param Integer $val
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author  Seif <ben.s@mipih.fr>
     */
    public function setPaperSizeIndex($val)
    {
        $pSize = ($val === ExportAdapterInterface::PAGE_SIZE_A3) ? PageSetup::PAPERSIZE_A3 : PageSetup::PAPERSIZE_A4;
        $this->getSpreadsheet()->getActiveSheet()
            ->getPageSetup()
            ->setPaperSize($pSize);

        return $this;
    }

    /**
     * @param $val
     *
     * @return $this
     */
    public function fontSize($val)
    {
        $this->styleObject['font']['size'] = $val;

        return $this;
    }

    /**
     * @return $this
     */
    public function fontBold()
    {
        $this->styleObject['font']['bold'] = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function fontItalic()
    {
        $this->styleObject['font']['italic'] = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function fontUnderline()
    {
        $this->styleObject['font']['underline'] = true;

        return $this;
    }

    /**
     * Getter $filename
     * @return string
     * @author  Seif <ben.s@mipih.fr>
     */
    public function getFilename()
    {
        // si ce n'est pas déjà setté, on lui attribue le nom par défaut
        if (null === $this->filename || 0 === strlen($this->filename)) {
            $this->setFilename(null);
        }

        return $this->filename;
    }

    /**
     * Setter $filename
     * Ajoute automatiquement l'extension du fichier
     *
     * @param string $filenameWithoutExt
     *
     * @return $this
     * @author  Seif <ben.s@mipih.fr>
     */
    public function setFilename($filenameWithoutExt)
    {
        $filenameWithoutExt = Std::strFrenchToEnglish($filenameWithoutExt);
        // nom par défaut si vide
        if (null === $filenameWithoutExt) {
            $filenameWithoutExt = 'gta_export';
        } elseif (!preg_match('/^[[:alnum:]'.preg_quote('-').'._\s]+$/', $filenameWithoutExt)) {
            throw new \LogicException($filenameWithoutExt);
        }

        // tronquer le nom de fichier si + de MAX_CARACTERS_FILE_NAME_COUNT caractères
        if (strlen($filenameWithoutExt) > self::MAX_CARACTERS_FILE_NAME_COUNT) {
            $filenameWithoutExt = substr($filenameWithoutExt, 0, self::MAX_CARACTERS_FILE_NAME_COUNT);
        }

        // construire le chemin complet
        $tmpDir = (null === self::EXPORT_PATH) ? sys_get_temp_dir() : self::EXPORT_PATH;
        $location = (is_writable($tmpDir)) ? $tmpDir.DIRECTORY_SEPARATOR : '';
        $this->filename =
            $location // path
            .$filenameWithoutExt // basename
            .$this->getFileExtension();

        return $this;
    }

    /**
     * @param $rowStart
     * @param $colStart
     * @param $rowEnd
     * @param $colEnd
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function enableZonalColumnFiltering($rowStart, $colStart, $rowEnd, $colEnd)
    {
        // the zone must include the column names, that's why you will notice à minus one applied to the row, yeaaaaah
        $cellStartRef = $this->getCellRefFromCoords($rowStart - 1, $colStart);
        $cellEndRef = $this->getCellRefFromCoords($rowEnd - 1, $colEnd);
        $this->getSpreadsheet()->getActiveSheet()->setAutoFilter($cellStartRef.':'.$cellEndRef);

        return $this;
    }

    /**
     * Example: To freeze first Row use (1,1)
     *
     * @param int $colCountFromLeft
     * @param int $rowCountFromTop
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function freezePane(int $colCountFromLeft, int $rowCountFromTop)
    {
        if (0 === $colCountFromLeft || 0 === $rowCountFromTop) {
            throw new \LogicException(
                'No my friend, you have to always provide non zero values for this function'
            );
        }
        $cellX = Coordinate::stringFromColumnIndex(++$colCountFromLeft);
        $cellY = ++$rowCountFromTop;
        $this->getCurrentWorkSheet()->freezePane($cellX.$cellY);
    }

    /**
     * Getter $spreadsheet
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     * @author  Seif <ben.s@mipih.fr>
     */
    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    /**
     * Convertit (1,2) à (A,2) avec vérification de validité
     *
     * @param Integer $row Y
     * @param Integer $col X
     *
     * @return String
     * @author  Seif <ben.s@mipih.fr>
     */
    public function getCellRefFromCoords($row, $col)
    {
        if (!is_int($row) || !is_int($col) || !$row || !$col) {
            throw new InvalidArgumentException('$row et $col doivent être des entiers non nuls');
        }

        return Coordinate::stringFromColumnIndex($col).$row;
    }

    /**
     * Convertit (C,5) en (3,5), avec vérification de validité
     *
     * @param Mixed $ref respectant le pattern 'A1, B22, C952, AA1, CB12 ...'
     *
     * @return array
     * @throws Exception
     * @author  Seif <ben.s@mipih.fr>
     */
    public function getCellCoordsFromRef($ref)
    {
        // $ref en tant que tableau est acceptée aussi avec le premier élément contenant la lettre
        $strRef = (is_array($ref)) ? implode('', $ref) : $ref;
        // convertir la reférence en tableau exp : AA11 => ['AA', 11]
        $coordinates = Coordinate::coordinateFromString($strRef);

        return [
            'iCol' => $coordinates[1],
            // convertir la chaine de caractère en numérique, exp : A => 1, B=> 2, Z => 26, AA => 27...
            'iRow' => Coordinate::columnIndexFromString($coordinates[0]),
        ];
    }

    /**
     * Description
     * @return mixed
     * @author  Seif <ben.s@mipih.fr>
     */
    public function getFileExtension()
    {
        return '.'.strtolower($this->fileFormat);
    }

    /**
     * @param $val
     *
     * @return $this
     */
    public function fontName($val)
    {
        $this->styleObject['font']['name'] = $val;

        return $this;
    }

    /**
     * Getter $fileFormat
     * @author  Seif <ben.s@mipih.fr>
     */
    public function getFileFormat()
    {
        return $this->fileFormat;
    }

    /**
     * Setter de $fileFormat
     *
     * @param String $format
     *
     * @return \Gta\DataExportBundle\Adapters\PhpSpreadsheet\PhpSpreadsheetAdapter
     * @author  Seif <ben.s@mipih.fr>
     */
    public function setFileFormat($format)
    {
        $this->fileFormat = $format;

        return $this;
    }

    /**
     * Définir les paramètres fu fichier ExcelWriterXml, à savoir le nom de l'auteur
     * et le nom du fichier
     *
     * @param String $author   Nom de l'UTEUR
     * @param String $filename Nom du fichier
     *
     * @return $this
     * @author  Seif <ben.s@mipih.fr>
     */
    public function setFileParams($author, $filename)
    {
        $this->getSpreadsheet()->getProperties()->setCreator($author);
        $this->getSpreadsheet()->getProperties()->setTitle($filename);

        return $this;
    }

    /**
     * Merge a range of cells
     *
     * @param integer $startRow
     * @param integer $startCol
     * @param integer $endRow
     * @param integer $endCol
     *
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function mergeCellsRange($startRow, $startCol, $endRow, $endCol)
    {
        $cellStart = $this->getCellRefFromCoords($startRow, $startCol);
        $cellEnd = $this->getCellRefFromCoords($endRow, $endCol);
        $this->getCurrentWorkSheet()->mergeCells($cellStart.':'.$cellEnd);

        return $this;
    }

    /**
     * Sets the sheet selected by default when opening the workbook
     *
     * @param int $index
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setActiveSheet($index = 0)
    {
        $this->getSpreadsheet()->setActiveSheetIndex($index);

        return $this;
    }

    /**
     * Appliquer une formule sur une colonne
     *
     * @param $colIndex
     * @param $rowStart
     * @param $rowEnd
     * @param $formula
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyColFormula($colIndex, $rowStart, $rowEnd, $formula)
    {
        $cellStartRef = $this->getCellRefFromCoords($rowStart, $colIndex);
        $cellEndRef = $this->getCellRefFromCoords($rowEnd, $colIndex);

        return '='.$formula.'('.$cellStartRef.':'.$cellEndRef.')';
    }

    /**
     * @param $width
     *
     * @return $this
     * @throws Exception
     */
    public function setDefaultWidth($width)
    {
        $this->getCurrentWorkSheet()->getDefaultColumnDimension()->setWidth($width);

        return $this;
    }

    /**
     * @param $height
     *
     * @return mixed
     * @throws Exception
     */
    public function setDefaultHeight($height)
    {
        $this->getCurrentWorkSheet()->getDefaultRowDimension()->setRowHeight($height);

        return $this;
    }

    /**
     * Setting orientation shortcut instead of calling setLandscape or setPortrait
     *
     * @param $orientation
     *
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setOrientation($orientation)
    {
        if (ExportAdapterInterface::ORIENTATION_LANDSCAPE === $orientation) {
            return $this->setLandscape();
        }
        if (ExportAdapterInterface::ORIENTATION_PORTRAIT === $orientation) {
            return $this->setPortrait();
        }
        throw new InvalidArgumentException('you have to precise a valid value for the orientation arg : '.$orientation);
    }

    /**
     * Print a row break on printing and pass to next page
     *
     * @param $rowIndex
     *
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setBreakRow($rowIndex)
    {
        $cellCoordinate = 'A'.$rowIndex;
        $this->getSpreadsheet()->getActiveSheet()->setBreak($cellCoordinate, Worksheet::BREAK_ROW);

        return $this;
    }

    /**
     * Print a col break on printing and pass to next page
     *
     * @param $colIndex
     *
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function setBreakCol($colIndex)
    {
        $cellCoordinate = $this->getCellRefFromCoords(1, $colIndex);
        $this->getSpreadsheet()->getActiveSheet()->setBreak($cellCoordinate, Worksheet::BREAK_COLUMN);

        return $this;
    }
}
