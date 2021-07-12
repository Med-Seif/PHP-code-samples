<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 19/02/2020 14:30
 */

namespace Gta\DataExportBundle\Configurator;

use Gta\CoreBundle\ParamConverter\MainFilter;
use Gta\CoreBundle\Utils\Yaml\YamlParserTrait;
use Gta\DataExportBundle\Exception\InvalidExportTitleException;
use Gta\DataExportBundle\Exception\MissingColNamesException;
use Gta\DataExportBundle\Exception\MissingColTitlesSectionException;
use Gta\DataExportBundle\Exception\MissingFileNameConfigException;
use Gta\DataExportBundle\Template\AbstractTemplate;
use Gta\Domain\DD\DataDictionary as DD;

/**
 * Class TemplateConfigurator
 *
 * @package Gta\DataExportBundle\Factory
 * @author  Seif <ben.s@mipih.fr> (19/02/2020/ 14:30)
 * @version 19
 */
class TemplateConfigurator
{
    use YamlParserTrait;
    /**
     * @var \Gta\CoreBundle\ParamConverter\MainFilter
     */
    private $mainFilter;

    /**
     * TemplateConfigurator constructor.
     *
     * @param \Gta\CoreBundle\ParamConverter\MainFilter $mainFilter
     */
    public function __construct(MainFilter $mainFilter)
    {
        $this->mainFilter = $mainFilter;
    }

    /**
     * @param \Gta\DataExportBundle\Template\AbstractTemplate $template
     * @param array                                           $contextParams
     *
     * @throws \Gta\CoreBundle\Exception\Authentication\InvalidPassedUserInstance
     * @author Seif <ben.s@mipih.fr>
     */
    public function buildContextParams(AbstractTemplate $template, array $contextParams)
    {
        $contextParams['codhop'] = $this->mainFilter->getCodhop();
        $contextParams['datdeb'] = $this->mainFilter->getDatdeb();
        $contextParams['datfin'] = $this->mainFilter->getDatfin();
        $contextParams['servic'] = $this->mainFilter->getTyptab().$this->mainFilter->getServic(
            ).$this->mainFilter->getSertyp(); // libellé service

        $contextParams['user'] = $this->mainFilter->getUser()->getPrenom().' '.$this->mainFilter->getUser()->getNom();
        $contextParams['libetb'] = $this->mainFilter->getUser()->getLibEtab(); // libellé hôpital
        $contextParams['typtab'] = $this->mainFilter->getTyptab();

        $template->setContextParams($contextParams);
    }

    /**
     * @param \Gta\DataExportBundle\Template\AbstractTemplate $template
     * @param                                                 $configFileName
     * @param                                                 $colTitlesSectionID
     * @param                                                 $fname
     *
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     * @throws \Gta\DataExportBundle\Exception\InvalidExportTitleException
     * @throws \Gta\DataExportBundle\Exception\MissingColNamesException
     * @throws \Gta\DataExportBundle\Exception\MissingColTitlesSectionException
     * @throws \Gta\DataExportBundle\Exception\MissingFileNameConfigException
     * @author Seif <ben.s@mipih.fr>
     */
    public function loadConfig(
        AbstractTemplate $template,
        $configFileName,
        $colTitlesSectionID,
        $fname
    ) {
        // charger TOUT le fichier yml
        $config = $this->parse($configFileName);

        // pas de section pour l'export en cours
        if (!isset($config[DD::COL_TITLES_KEY][$colTitlesSectionID])) {
            throw new MissingColTitlesSectionException($colTitlesSectionID, $configFileName);
        }
        // config de l'écran courant
        $currentUcConfig = $config[DD::COL_TITLES_KEY][$colTitlesSectionID];
        // config global, paramètres communs
        $globalConfig = $config[DD::GLOBAL_KEY];

        // un seul fichier pour les libellés, il faut récupérer la section qui nous intéresse
        if (!isset($currentUcConfig[DD::COLS_CONFIG_KEY])) {
            throw new MissingColNamesException($colTitlesSectionID, $configFileName);
        }
        $template->colTitles = $currentUcConfig[DD::COLS_CONFIG_KEY]; // récupérer le nom de colonnes

        // si pas de titre de classeur
        if (!isset ($currentUcConfig[DD::TITLE_CONFIG_KEY])) {
            throw new InvalidExportTitleException($colTitlesSectionID);
        }
        $template->screenTitle = $currentUcConfig[DD::TITLE_CONFIG_KEY]; // récupérer le nom de l'écran

        // charger la config d'orientation si déjà définie
        if (isset($currentUcConfig[DD::PRINT_ORIENTATION_CONFIG_KEY])) {
            $template->orientation = $currentUcConfig[DD::PRINT_ORIENTATION_CONFIG_KEY];
        }

        // taille papier d'impression
        if (isset($currentUcConfig[DD::PAPER_SIZE_CONFIG_KEY])) {
            $template->paperSize = $currentUcConfig[DD::PAPER_SIZE_CONFIG_KEY];
        }

        // marges papier
        if (isset($currentUcConfig[DD::PRINT_MARGIN_TOP_KEY])) {
            $template->marginTop = $currentUcConfig[DD::PRINT_MARGIN_TOP_KEY];
        }
        if (isset($currentUcConfig[DD::PRINT_MARGIN_BOTTOM_KEY])) {
            $template->marginBottom = $currentUcConfig[DD::PRINT_MARGIN_BOTTOM_KEY];
        }
        if (isset($currentUcConfig[DD::PRINT_MARGIN_LEFT_KEY])) {
            $template->marginLeft = $currentUcConfig[DD::PRINT_MARGIN_LEFT_KEY];
        }
        if (isset($currentUcConfig[DD::PRINT_MARGIN_RIGHT_KEY])) {
            $template->marginRight = $currentUcConfig[DD::PRINT_MARGIN_RIGHT_KEY];
        }

        /* paramètres globaux (chaque export peut définir ces params) */

        // entêtes impression
        $template->headerPrint = $globalConfig[DD::PRINT_HEADER_KEY];
        if (isset($currentUcConfig[DD::PRINT_HEADER_KEY])) {
            $template->headerPrint = $currentUcConfig[DD::PRINT_HEADER_KEY];
        }

        $template->footerPrint = $globalConfig[DD::PRINT_FOOTER_KEY];
        if (isset($currentUcConfig[DD::PRINT_FOOTER_KEY])) {
            $template->footerPrint = $currentUcConfig[DD::PRINT_FOOTER_KEY];
        }

        // Nom du fichier
        if (array_key_exists(DD::FILE_NAME_KEY, $currentUcConfig) && null !== $currentUcConfig[DD::FILE_NAME_KEY]) {
            $template->fileNameTemplate = $currentUcConfig[DD::FILE_NAME_KEY];
        } elseif (array_key_exists(DD::FILE_NAME_KEY, $globalConfig) && null !== $globalConfig[DD::FILE_NAME_KEY]) {
            $template->fileNameTemplate = $globalConfig[DD::FILE_NAME_KEY];
        } elseif (null !== $fname) {
            $template->fileNameTemplate = $fname;
        } else {
            throw new MissingFileNameConfigException($currentUcConfig, $globalConfig);
        }
    }

    /**
     * @param \Gta\DataExportBundle\Template\AbstractTemplate $template
     * @param                                                 $fileName
     *
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     * @author Seif <ben.s@mipih.fr>
     */
    public function loadStyleRules(AbstractTemplate $template, $fileName)
    {
        // les styles ont été déjà défnies
        if (count($template->style) > 0) {
            return;
        }


        $fileContent = file_get_contents($fileName);
        $styleRules = $this->parse($fileContent);// un fichier de style par export

        $extends = null;
        if (isset($styleRules[DD::IMPORT_KEY]) && is_array($styleRules[DD::IMPORT_KEY])) {
            $dir = dirname($fileName);
            foreach ($styleRules[DD::IMPORT_KEY] as $resource) {
                $file = $dir.DIRECTORY_SEPARATOR.$resource;
                if (file_exists($file)) {
                    $extends .= file_get_contents($file);
                }
            }
        }
        $fileContent = str_replace('&', '*', $fileContent);
        $content = null;
        if (strlen($extends) > 0) {
            $content = $extends."\n".$fileContent;
        }
        $result = $this->parse($content);

        if (isset($result[DD::STYLE_CONFIG_KEY])) {
            $template->style = $result[DD::STYLE_CONFIG_KEY];
        }
    }
}
