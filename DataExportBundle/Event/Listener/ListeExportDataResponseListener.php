<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 04/02/2019 11:20
 */

namespace Gta\DataExportBundle\Event\Listener;

use Doctrine\Common\Annotations\AnnotationReader;
use Gta\CoreBundle\ParamConverter\MainFilter;
use Gta\CoreBundle\Utils\Wrapper\DataWrapperInterface;
use Gta\CoreBundle\Utils\Wrapper\TableWrapper;
use Gta\DataExportBundle\Annotation\ExportAnnotation;
use Gta\DataExportBundle\Factory\TemplateFactory;
use Gta\DataExportBundle\Template\AbstractTemplate;
use Gta\MedicalBundle\Repository\GmservRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * Class ExportDataViewListener
 * @package Gta\DataExportBundle\Event\Listener
 * @author  Seif <ben.s@mipih.fr>
 */
class ListeExportDataResponseListener extends AbstractExportResponse
{
    /**
     * @var DataWrapperInterface
     */
    private $wrapper;
    /**
     * @var \Gta\DataExportBundle\Factory\TemplateFactory
     */
    private $templateFactory;
    /**
     * @var AnnotationReader
     */
    private $annotationReader;
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $mainFilter;
    /**
     * @var \Gta\MedicalBundle\Repository\GmservRepository
     */
    private $gmservRepository;
    /**
     * @var \Symfony\Component\Serializer\Encoder\DecoderInterface
     */
    private $decoder;

    /**
     * ExportDataViewListener constructor.
     *
     * @param \Symfony\Component\Serializer\Encoder\DecoderInterface $decoder
     * @param \Gta\CoreBundle\Utils\Wrapper\TableWrapper             $wrapper
     * @param \Gta\DataExportBundle\Factory\TemplateFactory          $templateFactory
     * @param \Doctrine\Common\Annotations\AnnotationReader          $annotationReader
     *
     *
     * @param \Gta\CoreBundle\ParamConverter\MainFilter              $mainFilter
     * @param \Gta\MedicalBundle\Repository\GmservRepository         $gmservRepository
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function __construct(
        DecoderInterface $decoder,
        TableWrapper $wrapper,
        TemplateFactory $templateFactory,
        AnnotationReader $annotationReader,
        MainFilter $mainFilter,
        GmservRepository $gmservRepository
    ) {
        $this->decoder = $decoder;
        $this->wrapper = $wrapper;
        $this->templateFactory = $templateFactory;
        $this->annotationReader = $annotationReader;
        $this->mainFilter = $mainFilter;
        $this->gmservRepository = $gmservRepository;
    }


    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @throws \Gta\CoreBundle\Exception\Authentication\InvalidPassedUserInstance
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     * @throws \Gta\DataExportBundle\Exception\InvalidExportTitleException
     * @throws \Gta\DataExportBundle\Exception\InvalidTemplateClassException
     * @throws \Gta\DataExportBundle\Exception\MissingColNamesException
     * @throws \Gta\DataExportBundle\Exception\MissingColTitlesFileException
     * @throws \Gta\DataExportBundle\Exception\MissingColTitlesSectionException
     * @throws \Gta\DataExportBundle\Exception\MissingFileNameConfigException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        // règles d'entrée
        if (false === $this->supportSimpleListDataExport($request, $response)) {
            return;
        }
        // go
        $this->log('______________________________Event triggered : Export Process Start', [__CLASS__, __FUNCTION__]);
        // récupérer les données à exporter
        $content = $response->getContent();

        // décodage, rappel: sauf les réponses de type JSON sont acceptées (à voir avec l'évolution du besoin)
        $responseDataArray = $this->decoder->decode($content, 'json');

        // récupérer les clefs de données dans le tableau
        $dataKey = $this->wrapper->getDataKey();
        $extraDataKey = $this->wrapper->getExtraDataKey();

        // récupération données pour l'export
        $data = isset($responseDataArray[$dataKey]) ? $responseDataArray[$dataKey] : $responseDataArray;

        // Init context params
        $contextParams = isset($responseDataArray[$extraDataKey]) ? $responseDataArray[$extraDataKey] : [];
        if ($request->query->has('name')) {
            $contextParams['name'] = $request->query->get('name');
        }

        // récupérer l'annotation
        $annotation = $this->getAnnotation($request);

        // la route sera utilisé comme identifiant de section dans le fichier de titres de colonnes
        $requestRoute = $request->attributes->get(self::ROUTE);
        $this->log('Export request route', [$requestRoute]);
        $this->log('Query params', [$request->attributes->all()]);

        // construire le template de l'export en cours
        $template = $this->getTemplateObject($requestRoute, $annotation, $contextParams);

        // récupérer format de l'export
        $format = $this->getExportFormat($request);

        // générer le fichier
        $fileResponse = $this->getResponseFile($template, $format, $data);

        // le premier qui change la réponse gagne! (stopPropagation derrière), et voilà!!
        $event->setResponse($fileResponse);
        $this->log('----Export process END----');
    }

    /**
     * @param                                                        $currentRouteName
     * @param \Gta\DataExportBundle\Annotation\ExportAnnotation|null $annotation
     *
     * @param array                                                  $contextParams
     *
     * @return mixed
     * @throws \Gta\CoreBundle\Exception\Authentication\InvalidPassedUserInstance
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     * @throws \Gta\DataExportBundle\Exception\InvalidExportTitleException
     * @throws \Gta\DataExportBundle\Exception\InvalidTemplateClassException
     * @throws \Gta\DataExportBundle\Exception\MissingColNamesException
     * @throws \Gta\DataExportBundle\Exception\MissingColTitlesFileException
     * @throws \Gta\DataExportBundle\Exception\MissingColTitlesSectionException
     * @throws \Gta\DataExportBundle\Exception\MissingFileNameConfigException
     * @author Seif <ben.s@mipih.fr>
     */
    private function getTemplateObject(
        $currentRouteName,
        ExportAnnotation $annotation = null,
        array $contextParams = []
    ) : AbstractTemplate {
        // valeurs par défaut
        $templateClassName = null;
        $styleFileName = null;
        $colTitlesSectionID = $currentRouteName;

        // une option template a été définie dans l'annotation
        if (null !== $annotation) {
            // les champs de l'annotation n'etant pas tous obligatoires, il faut des tests
            // pour écraser les valeurs par défaut ou non
            $annotationId = $annotation->getId();
            $annotationTemplate = $annotation->getTemplateClassName();
            $annotationStyle = $annotation->getStyleFileName();

            if (null !== $annotationTemplate) {
                $templateClassName = $annotationTemplate;
            }
            if (null !== $annotationStyle) {
                $styleFileName = $annotationStyle;
            }
            // si pas de ID spécifié, la route courante sera utilisée
            if (null !== $annotationId) {
                $colTitlesSectionID = $annotationId;
            }
        }
        $template = $this->templateFactory->createTemplate(
            $colTitlesSectionID,
            $templateClassName,
            $styleFileName,
            $contextParams
        );
        $this->log('Template instanciation success', array(get_class($template)));

        return $template;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return ExportAnnotation
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    private function getAnnotation(Request $request)
    {
        $controllerActionNames = $this->getControllerActionNames($request);
        $controller = $controllerActionNames['controller'];
        $action = $controllerActionNames['action'];

        // lire l'annotation
        return $this->annotationReader->getMethodAnnotation(
            new \ReflectionMethod(
                $controller,
                $action
            ),
            ExportAnnotation::class
        );
    }

    /**
     * @param \Gta\DataExportBundle\Template\AbstractTemplate $template
     *
     * @param string                                          $format
     * @param array                                           $data
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author Seif <ben.s@mipih.fr>
     */
    private function getResponseFile(AbstractTemplate $template, $format, $data = null)
    {
        $this->log('File generation process start', isset($data[0]) ? $data[0] : []);
        $filename = null;
        do {
            if (file_exists($filename)) {
                // construire la réponse HTTP de type FILE
                $response = new BinaryFileResponse($filename);
                $response->setContentDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $response->getFile()->getFilename()
                );
                $this->log(
                    'File generation success',
                    array($response->getFile()->getFilename())
                );

                return $response;
            }
            $filename = $template
                ->generateFile($data)
                ->setFileFormat($format)// préciser le format de l'export
                ->generateFileName()
                ->save();
        } while (true);

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author Seif <ben.s@mipih.fr>
     */
    protected function supportSimpleListDataExport(Request $request, Response $response)
    {
        return ('list' === $request->get($this->getQueryTypeParamName()) && $this->supportsExports($request, $response));
    }
}