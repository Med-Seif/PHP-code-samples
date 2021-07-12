<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 08/02/2019 09:31
 */

namespace Gta\DataExportBundle\Template;

use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class AbstractTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (08/02/2019/ 09:31)
 * @version 19
 */
abstract class AbstractTemplate
{
    /**
     * @var array
     */
    public $style = [];
    /**
     * @var array
     */
    public $colTitles = [];
    /**
     * @var string
     */
    public $screenTitle;
    /**
     * @var string
     */
    public $headerPrint;
    /**
     * @var string
     */
    public $footerPrint;
    /**
     * @var string
     */
    public $orientation = ExportAdapterInterface::ORIENTATION_LANDSCAPE;
    /**
     * @var int
     */
    public $paperSize = ExportAdapterInterface::PAGE_SIZE_A4;
    /**
     * @var int
     */
    public $marginTop = 1;
    /**
     * @var int
     */
    public $marginBottom = 1;
    /**
     * @var int
     */
    public $marginLeft = 1;
    /**
     * @var int
     */
    public $marginRight = 1;
    /**
     * @var string
     */
    public $fileNameTemplate;
    /**
     * @var ExportAdapterInterface
     */
    protected $exportAdapter;
    /**
     * @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage
     */
    protected $el;
    /**
     * @var array
     */
    protected $contextParams = [];

    /**
     * l'idée de ce constructeur est de décomplexifier au maximum l'instanciation de cette classe
     * pour nous retourner une instance prête pour la génération du fichier d'export depuis son constructeur
     * et pour cette même raison que seul la méthode 'generate' et le constructeur sont publiques
     *
     * @param \Gta\DataExportBundle\Adapters\ExportAdapterInterface    $exportAdapter
     *
     * @param \Symfony\Component\ExpressionLanguage\ExpressionLanguage $el
     */
    final public function __construct(
        ExportAdapterInterface $exportAdapter,
        ExpressionLanguage $el
    ) {
        $this->exportAdapter = $exportAdapter;
        $this->el = $el;
    }

    /**
     * @param mixed $data Données à exporter
     *
     * @return \Gta\DataExportBundle\Template\AbstractTemplate
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function generateFile($data);

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    final public function save()
    {
        // écrire le fichier physiquement et retourner le nom du fichier
        return $this
            ->exportAdapter
            ->writeToFile()
            ->getFileName();
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    final public function getScreenTitle()
    {
        // si c un tableau, alors c un export sur plusieurs pages
        if (is_array($this->screenTitle)) {
            // récupérer l'index de la feuille courante
            $index = $this->exportAdapter->getCurrentWorkSheetIndex();

            return $this->screenTitle[$index];
        }

        return $this->screenTitle;
    }

    /**
     * @param string $screenTitle
     *
     * @return AbstractTemplate
     * @author Seif <ben.s@mipih.fr>
     */
    final public function setScreenTitle($screenTitle)
    {
        $this->screenTitle = $screenTitle;

        return $this;
    }

    /**
     * @param $format
     *
     * @return \Gta\DataExportBundle\Template\AbstractTemplate
     * @author Seif <ben.s@mipih.fr>
     */
    final public function setFileFormat($format)
    {
        $this->exportAdapter->setFileFormat($format);

        return $this;
    }

    /**
     * @return \Gta\DataExportBundle\Template\AbstractTemplate
     * @author Seif <ben.s@mipih.fr>
     */
    final public function generateFileName()
    {
        $fileName = $this->getFileName();
        $this->exportAdapter->setFilename($fileName);

        return $this;
    }

    /**
     * Chaque template dorénavant génére son nom de fichier
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function getFileName();

    /**
     * @param array $contextParams
     *
     * @return AbstractTemplate
     */
    public function setContextParams(array $contextParams): AbstractTemplate
    {
        $this->contextParams = $contextParams;

        return $this;
    }

    /**
     * @return \Gta\DataExportBundle\Adapters\ExportAdapterInterface
     * @author Seif <ben.s@mipih.fr>
     */
    final protected function getExportAdapter()
    {
        return $this->exportAdapter;
    }
}