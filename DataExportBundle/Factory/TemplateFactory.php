<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 11/02/2019 12:20
 */

namespace Gta\DataExportBundle\Factory;

use Gta\CoreBundle\Expression\GtaExpressionLanguage;
use Gta\CoreBundle\Resolver\ExtraOptionsResolver;
use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Configurator\TemplateConfigurator;
use Gta\DataExportBundle\Exception\InvalidTemplateClassException;
use Gta\DataExportBundle\Exception\MissingColTitlesFileException;
use Gta\DataExportBundle\Template\AbstractTemplate;
use Gta\DataExportBundle\Template\SimpleTableTemplate;
use Gta\Domain\DD\DataDictionary as DD;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class TemplateFactory
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (11/02/2019/ 15:03)
 * @version 19
 */
class TemplateFactory
{
    const DEFAULT_TEMPLATE_CLASS_NAME           = SimpleTableTemplate::class; # la classe à charger
    const DEFAULT_STYLE_FILE_NAME               = 'default_table.yml'; # nom de fichier de style par défaut
    const DEFAULT_STYLE_FILES_PATH              = '/src/Gta/DataExportBundle/StyleSheet/';
    const DEFAULT_COL_TITLES_COMPLETE_FILE_NAME = '/src/Gta/DataExportBundle/Resources/config/simple_list_config.yml';
    const CONTEXT_PARAMS                        = array(
        DD::CODHOP => '?',
        DD::DATDEB => '?',
        DD::DATFIN => '?',
        DD::SERVIC => '?',
        DD::USER   => '?',
        DD::LIBETB => '?',
        DD::TYPTAB => '?',
    );
    /**
     * @var string
     */
    private $styleFilesPath;
    /**
     * @var string
     */
    private $configFileName;
    /**
     * @var \Gta\DataExportBundle\Adapters\ExportAdapterInterface
     */
    private $exportAdapter;
    /**
     * @var \Gta\CoreBundle\Resolver\ExtraOptionsResolver
     */
    private $extraOptionsResolver;
    /**
     * @var GtaExpressionLanguage
     */
    private $el;
    /**
     * @var string
     */
    private $fname;
    /**
     * @var \Gta\DataExportBundle\Configurator\TemplateConfigurator
     */
    private $configurator;

    /**
     * TemplateFactory constructor.
     *
     * @param string                                                  $fname defined in services.yml as a parameter
     * @param \Gta\DataExportBundle\Adapters\ExportAdapterInterface   $exportAdapter
     * @param \Gta\CoreBundle\Resolver\ExtraOptionsResolver           $extraOptionsResolver
     * @param \Gta\CoreBundle\Expression\GtaExpressionLanguage        $el
     * @param \Symfony\Component\HttpKernel\Kernel                    $kernel
     * @param \Gta\DataExportBundle\Configurator\TemplateConfigurator $configurator
     */
    final public function __construct(
        string $fname,
        ExportAdapterInterface $exportAdapter,
        ExtraOptionsResolver $extraOptionsResolver,
        GtaExpressionLanguage $el,
        Kernel $kernel,
        TemplateConfigurator $configurator
    ) {
        // les deux premiers peuvent être écrasées depuis l'annotation ;)
        // et les valeurs par défaut sont définies en tant que paramètres
        // dans services.yml de @DataExportBundle
        $this->fname = $fname;
        $projectDir = $kernel->getProjectDir();
        $this->styleFilesPath = $projectDir.self::DEFAULT_STYLE_FILES_PATH; // path par défaut des fichiers de style
        // chemin complet de fichiers de titres de colonnes
        $this->configFileName = $projectDir.self::DEFAULT_COL_TITLES_COMPLETE_FILE_NAME;
        $this->exportAdapter = $exportAdapter;
        $this->extraOptionsResolver = $extraOptionsResolver;
        $this->el = $el;
        $this->configurator = $configurator;
    }

    /**
     * @param string      $colTitlesSectionID
     * @param string|null $templateClassName
     * @param string|null $styleFileName
     *
     * @param array       $contextParams
     *
     * @return \Gta\DataExportBundle\Template\AbstractTemplate
     * @throws \Gta\CoreBundle\Exception\Authentication\InvalidPassedUserInstance
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     * @throws \Gta\DataExportBundle\Exception\InvalidExportTitleException
     * @throws \Gta\DataExportBundle\Exception\InvalidTemplateClassException
     * @throws \Gta\DataExportBundle\Exception\MissingColNamesException
     * @throws \Gta\DataExportBundle\Exception\MissingColTitlesFileException
     * @throws \Gta\DataExportBundle\Exception\MissingColTitlesSectionException
     * @throws \Gta\DataExportBundle\Exception\MissingFileNameConfigException
     * @author Seif <ben.s@mipih.fr>
     */
    final public function createTemplate(
        $colTitlesSectionID,
        $templateClassName = null,
        $styleFileName = null,
        $contextParams = []
    ): AbstractTemplate {
        /***** Fabrique paramètres *****/

        if (null === $templateClassName) {
            // une option template a été définie dans l'annotation
            $templateClassName = self::DEFAULT_TEMPLATE_CLASS_NAME;
        }

        if (null === $styleFileName) {
            // option style
            $styleFileName = self::DEFAULT_STYLE_FILE_NAME;
        }

        if (!class_exists($templateClassName)) {
            throw new InvalidTemplateClassException($templateClassName);
        }

        // paramètres de contexte (codhop, user etc...)
        $resolver = $this->extraOptionsResolver;
        $resolver->setDefaults(self::CONTEXT_PARAMS);
        $contextParams = $resolver->resolve($contextParams);

        /***** Fabrique de l'instance *****/
        /** @var \Gta\DataExportBundle\Template\AbstractTemplate $template */
        $template = new $templateClassName(
            $this->exportAdapter,
            $this->el
        );

        /***** Configuration de l'instance *****/
        $this->configurator->buildContextParams($template, $contextParams);

        if (null !== $this->styleFilesPath && null !== $styleFileName) {
            if (!file_exists($styleFile = $this->styleFilesPath.$styleFileName)) {
                throw new MissingColTitlesFileException($styleFile);
            }
            $this->configurator->loadStyleRules($template, $styleFile);
        }

        //chargement config
        if (null !== $this->configFileName && null !== $colTitlesSectionID) {
            if (!file_exists($this->configFileName)) {
                throw new MissingColTitlesFileException($this->configFileName);
            }
            $this->configurator->loadConfig($template, $this->configFileName, $colTitlesSectionID, $this->fname);
        }

        return $template;
    }
}