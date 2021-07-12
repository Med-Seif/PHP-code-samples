<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia
 * Date 17/01/2020 on  11:41
 */

namespace Gta\DataExportBundle\Event\Listener;

use Gta\CoreBundle\Expression\GtaExpressionLanguage;
use Gta\CoreBundle\ParamConverter\MainFilter;
use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Manager\SpreadSheetManager;
use Gta\Domain\DD\DataDictionary as DD;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * TsExportResponseListener is a response event listener
 * It has a role to run tableau de service export data
 * The request must has  export and format params  as (planning Xlsx)
 *
 * Class TsExportResponseListener
 * @package Gta\DataExportBundle\Event\Listener
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 17/02/2020 on  20:17
 */
class TsExportResponseListener extends AbstractExportResponse
{
    const CONTENT_TYPE_XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    const FILE_NAME_PREFIX  = 'TS_Collectif';

    const RESPONSE_STREAMED = false;

    /**
     * @var ExportAdapterInterface
     */
    private $adapter;
    /**
     * @var SpreadSheetManager
     */
    private $manager;
    /**
     * @var \Gta\CoreBundle\ParamConverter\MainFilter
     */
    private $mainFilter;
    /**
     * @var string
     */
    private $fname;
    /**
     * @var \Gta\CoreBundle\Expression\GtaExpressionLanguage
     */
    private $el;
    /**
     * @var \Gta\DataExportBundle\Event\Listener\TsExportDataSourceStrategyInterface
     */
    private $dataSourceStrategy;

    /**
     * TsExportResponseListener constructor.
     *
     * @param                                                                          $fname
     * @param \Gta\DataExportBundle\Adapters\ExportAdapterInterface                    $adapter
     * @param \Gta\DataExportBundle\Manager\SpreadSheetManager                         $manager
     * @param \Gta\CoreBundle\ParamConverter\MainFilter                                $mainFilter
     * @param \Gta\CoreBundle\Expression\GtaExpressionLanguage                         $el
     * @param \Gta\DataExportBundle\Event\Listener\TsExportDataSourceStrategyInterface $dataSourceStrategy
     */
    public function __construct(
        $fname,
        ExportAdapterInterface $adapter,
        SpreadSheetManager $manager,
        MainFilter $mainFilter,
        GtaExpressionLanguage $el,
        TsExportDataSourceStrategyInterface $dataSourceStrategy
    ) {
        $this->fname = $fname;
        $this->adapter = $adapter;
        $this->manager = $manager;
        $this->mainFilter = $mainFilter;
        $this->el = $el;
        $this->dataSourceStrategy = $dataSourceStrategy;
    }

    /**
     * @param FilterResponseEvent $event
     *
     * @throws \Gta\CoreBundle\Exception\Authentication\InvalidPassedUserInstance
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author mberrekia
     * Date 17/01/2020 on  11:55
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        /** @var Request $request */
        $request = $event->getRequest();
        /** @var Response $response */
        $response = $event->getResponse();

        // Check if it supports export data
        if (false === $this->supportsPlanningExport($request, $response)) {
            return;
        }

        // On your marks, get set; go! ...to export data
        $this->log(
            '______________________________Event triggered : Tableau de service export Process Start',
            [__CLASS__, __FUNCTION__]
        );

        $data = $this->dataSourceStrategy->loadData($event->getResponse());

        $modelId = $this->getUniqueModelId($request);

        // Set spreadsheet file name
        # tu as tout dans le le mainfilter MAAAN
        $params = [
            DD::USER         => $this->mainFilter->getUser()->getPrenom().'-'.$this->mainFilter->getUser()->getNom(),
            DD::CODHOP       => $this->mainFilter->getCodhop(),
            DD::SERVIC       => $this->mainFilter->getTyptab().$this->mainFilter->getServic(
                ).$this->mainFilter->getSertyp(),
            DD::DATDEB       => $this->mainFilter->getDatdeb(),
            DD::DATFIN       => $this->mainFilter->getDatfin(),
            DD::SCREEN_TITLE => self::FILE_NAME_PREFIX,
        ];
        $filename = $this->el->evaluate($this->fname, $params);

        $this->manager->setName($filename);

        if (true === self::RESPONSE_STREAMED) {
            $response = $this->getStreamedResponse($filename.'.xlsx', $data, $modelId);
        } else {
            $response = $this->getResponseFile($filename, $data, $modelId);
        }
        $event->setResponse($response);
        $this->log('______________________________Tableau de service export Process END');
    }

    /**
     * @param Request                                    $request
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 22/01/2020 on  14:25
     */
    protected function supportsPlanningExport(Request $request, Response $response)
    {
        return ('planning' === $request->get($this->getQueryTypeParamName()) && $this->supportsExports(
                $request,
                $response
            ));
    }

    /**
     * @param $filename
     * @param $data
     * @param $modelId
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @author Seif <ben.s@mipih.fr>
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    private function getStreamedResponse($filename, $data, $modelId)
    {
        // Streamed Response instance
        $streamedResponse = new StreamedResponse();
        $streamedResponse->headers->set('Content-Type', self::CONTENT_TYPE_XLSX);
        $disposition = $streamedResponse->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );
        $streamedResponse->headers->set('Content-Disposition', $disposition);
        $streamedResponse->headers->set('Cache-Control', 'max-age=0');
        $streamedResponse->sendHeaders();

        // Callback work
        $streamedResponse->setCallback(
            function () use ($data, $modelId) {
                $this->manager->buildWorkSheet($data, $modelId)->sendFile();
            }
        );

        $streamedResponse->send();

        return $streamedResponse;
    }

    /**
     * @param $filename
     * @param $data
     * @param $modelId
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     * @throws \Gta\CoreBundle\Exception\Authentication\InvalidPassedUserInstance
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author Seif <ben.s@mipih.fr>
     */
    private function getResponseFile($filename, $data, $modelId)
    {
        $completeFileNameWithPath = null;
        do {
            if (file_exists($completeFileNameWithPath)) {
                // construire la réponse HTTP de type FILE
                $response = new BinaryFileResponse($completeFileNameWithPath);
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
            $completeFileNameWithPath = $this->manager->buildWorkSheet($data, $modelId)->writeFile($filename);
        } while (true);

        return null;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 17/02/2020 on  20:38
     */
    private function getUniqueModelId(Request $request)
    {
        return $request->get('modelId');
    }

}