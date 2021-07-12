<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 25/02/2020 17:12
 */

namespace Gta\DataExportBundle\Configurator;

use Gta\CoreBundle\Resolver\ExtraOptionsResolver;
use Gta\CoreBundle\Utils\Yaml\YamlParserTrait;
use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Builder\YmlConfigArrayAccess;
use Gta\DataExportBundle\Planning\Model\AbstractTsModel as SuperModel;
use Gta\DataExportBundle\Utils\TsKey;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\OptionsResolver\Options;

/**
 * Class TsTemplateConfigurator
 *
 * @package Gta\DataExportBundle\Configurator
 * @author  Seif <ben.s@mipih.fr> (25/02/2020/ 17:14)
 * @version 19
 */
class TsTemplateConfigurator
{
    use YamlParserTrait;
    const USER_PARAMETRS_LIST = array(
        'paperSizeIndex',
        'orientation',
        'cell_show_activite',
        'cell_show_indicateurs',
        'cell_show_couverture',
        'cell_show_remuneration',
    );
    /**
     * @var string
     */
    private static $configFilePath;
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;
    /**
     * @var \Gta\CoreBundle\Resolver\ExtraOptionsResolver
     */
    private $optionsResolver;

    /**
     * AbstractTsModel constructor.
     *
     * @param \Symfony\Component\HttpKernel\Kernel           $kernel
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Gta\CoreBundle\Resolver\ExtraOptionsResolver  $optionsResolver
     */
    public function __construct(Kernel $kernel, RequestStack $requestStack, ExtraOptionsResolver $optionsResolver)
    {
        # why am 'I doing this...I don't know why Mansour
        # Since three models are created as services, this constructor will be invoked three times and the file path is unique..YEAH
        # je travaille mon anglais un peu ... tu sais!
        if (!self::$configFilePath) {
            self::$configFilePath = $kernel->getProjectDir().'/src/Gta/DataExportBundle/Resources/config/ts_config.yml';
        }
        $this->requestStack = $requestStack;
        $this->optionsResolver = $optionsResolver;
    }

    /**
     * @param \Gta\DataExportBundle\Planning\Model\AbstractTsModel $model
     *
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     * @author Seif <ben.s@mipih.fr>
     */
    public function configure(SuperModel $model)
    {
        # faire la correspondance entre le UID du modèle et l nom de sa section dans le fichier de config
        $getConfigSection = function ($id) {
            if (SuperModel::M_DATE_HORAIRE_INTERVENANT_MODEL === $id) {
                return 'date_horaire_intervenant_model';
            }
            if (SuperModel::M_INTERVENANT_DATE_HORAIRE_MODEL === $id) {
                return 'intervenant_date_horaire_model';
            }
            if (SuperModel::M_DATE_INTERVENANT_HORAIRE_MODEL === $id) {
                return 'date_intervenant_horaire_model';
            }
        };

        $config = $this->parse(self::$configFilePath);
        $sectionID = $getConfigSection($model->getUid());
        $getModelConfig = function ($configGroup) use ($config, $sectionID) {
            $modelConfig = $config[$configGroup][$sectionID];
            if (null === $modelConfig) {
                return [];
            }

            return $modelConfig;
        };


        # charger les styles YEAH
        $commonStyle = $config['options']['common'];
        $modelStyle = $getModelConfig('options');
        # charger les options d'impression MAN
        $commonPrint = $config['print']['common'];
        $modelPrint = $getModelConfig('print');
        # le global a la priorité sur le spécifique
        $mergedStyle = array_replace_recursive($commonStyle, $modelStyle);
        $mergedprint = array_replace_recursive($commonPrint, $modelPrint);
        # all the config will be centralized in this class to be easily overridden later
        YmlConfigArrayAccess::createConfigs(
            array(
                array(
                    TsKey::K_HEADER,
                    $mergedStyle[TsKey::K_HEADER],
                ),
                array(
                    'left',
                    $mergedStyle[TsKey::K_BODY][TsKey::K_LEFT_SIDE],
                ),
                array(
                    TsKey::K_BODY,
                    $mergedStyle[TsKey::K_BODY][TsKey::K_BODY],
                ),
                array(
                    TsKey::K_PRINT_METHOD_CALLS,
                    $mergedprint[TsKey::K_PRINT_CALLS],
                ),
                array(
                    TsKey::K_PRINT_OPTIONS,
                    $mergedprint[TsKey::K_OPTIONS],
                ),
            )
        );
        # override default config by user config
        $this->applyUserInputOnConfig();
    }

    /**
     * Herer we will override the default config by user submitted parameters (coming from front popup in our case)
     *
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyUserInputOnConfig()
    {
        # callback to normalize false or zéro params
        $normalizeRequestBooleanValues = function (Options $options, $value) {
            if (in_array(strtolower(trim($value)), ['false', '0'])) {
                return false;
            }

            return true;
        };
        $normalizePaperSizeIndes = function (Options $options, $value) {

            if ('A3' === $value) {
                return ExportAdapterInterface::PAGE_SIZE_A3;
            }

            return ExportAdapterInterface::PAGE_SIZE_A4;
        };
        # configure options resolver
        $resolver = $this->optionsResolver->setAllowExtraOptions(true)
            ->setDefined(self::USER_PARAMETRS_LIST)
            ->multiSetNormaizer(
                [
                    TsKey::K_CELL_SHOW_ACTIVITE,
                    TsKey::K_CELL_SHOW_REMUNERATION,
                    TsKey::K_CELL_SHOW_INDICATEURS,
                    TsKey::K_CELL_SHOW_COUVERTURE,
                ],
                $normalizeRequestBooleanValues
            )
            ->setNormalizer(TsKey::K_PRINT_PAPER_SIZE_INDEX, $normalizePaperSizeIndes)
            ->setAllowedValues(TsKey::K_PRINT_PAPER_SIZE_INDEX, ['A3', 'A4'])
            ->setAllowedValues(TsKey::K_PRINT_ORIENTATION, ['landscape', 'portrait'])
            ->multiSetAllowedValues(
                [
                    TsKey::K_CELL_SHOW_ACTIVITE,
                    TsKey::K_CELL_SHOW_REMUNERATION,
                    TsKey::K_CELL_SHOW_INDICATEURS,
                    TsKey::K_CELL_SHOW_COUVERTURE,
                ],
                [
                    'true',
                    'false',
                    '1',
                    '0',
                ]
            );
        # get submitted query, notice that we get here only $_GET parameters, NB: we can modify here our user parameters source
        $userParams = $this->requestStack->getCurrentRequest()->query->all();
        # resolve parameters, Thibault notice here that we are using our home made OptionsResolver wich takes extra parameters
        $resolverUserParams = $resolver->resolve($userParams);
        # apply user config
        YmlConfigArrayAccess::overrideConfigParams($resolverUserParams);
        # pars en pause Thibault
    }
}