<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia
 * Date 21/01/2020 on  09:58
 */

namespace Gta\DataExportBundle\Event\Listener;

use Gta\CoreBundle\Event\GetControllerActionFromRequestTrait;
use Gta\CoreBundle\Log\GtaLoggerTrait;
use Gta\CoreBundle\Security\User\AuthenticatedUserTrait;
use Gta\CoreBundle\Security\User\UserAwareInterface;
use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractExportResponse
 * @package Gta\DataExportBundle\Event\Listener
 * @author  mberrekia <berrekia.m@mipih.fr>
 */
class AbstractExportResponse implements UserAwareInterface, LoggerAwareInterface
{
    use AuthenticatedUserTrait, GetControllerActionFromRequestTrait, GtaLoggerTrait;
    const ROUTE = '_route';

    /**
     * @var string
     */
    private $queryFormatParamName;
    /**
     * @var string
     */
    private $queryTypeParamName;

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getQueryFormatParamName()
    {
        return $this->queryFormatParamName;
    }

    /**
     * @param string $queryFormatParamName
     *
     * @author Seif <ben.s@mipih.fr> et ne change pas l'auteur !! j'ai cru que se sont de nouvelles
     *                fonctionnalités!! MAAAAAAN Tu sais bien que le port des armes est légal aux States, fais gaffe,
     *                je suis méchant
     */
    public function setQueryFormatParamName($queryFormatParamName)
    {
        $this->queryFormatParamName = $queryFormatParamName;
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public function getQueryTypeParamName()
    {
        return $this->queryTypeParamName;
    }

    /**
     * @param $queryTypeParamName
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function setQueryTypeParamName($queryTypeParamName)
    {
        $this->queryTypeParamName = $queryTypeParamName;
    }

    /**
     * @param Request $request
     *
     * @return mixed|string
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author Seif <ben.s@mipih.fr>
     */
    protected function getExportFormat(Request $request)
    {
        // récupérer format de l'export
        $format = $request->get($this->getQueryFormatParamName());
        // format par défaut si format est vide
        $format = trim($format) ? ucfirst($format) : ExportAdapterInterface::FORMAT_XLSX;
        $this->log('Matched format', array($format));

        return $format;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author Seif <ben.s@mipih.fr>
     */
    protected function supportsExports(Request $request, Response $response)
    {
        $route = $request->attributes->get(self::ROUTE);
        if (in_array(
            $route,
            ['_wdt', '_profiler', '_errors', '_main']
        )) {
            return false;
        }
        // paramètre format non renseigné = pas d'export aussi
        if (null === $request->get($this->getQueryFormatParamName()) || null === $request->get(
                $this->getQueryTypeParamName()
            )) {
            // Missing query param
            return false; // on quitte directement, inutile logger so non on va bombarder le fichier de log
        }
        $origin = [];
        // user non authentifié = pas d'export
        if ($response instanceof JWTAuthenticationFailureResponse) {
            $origin[] = 'Non authenticated';
        }
        // la requête est de type ajax = pareil
        if ($request->isXmlHttpRequest()) {
            $origin[] = 'Is AJAX request';
        }

        // il s'agit d'un closure (profileur symfony par exp) = pas d'export
        if (false === ($this->getControllerActionNames($request))) {
            $origin[] = 'Not a MVC controller';
        }

        if (0 !== count($origin)) {
            $this->log(
                json_encode($origin),
                array($request->attributes->get(self::ROUTE), $request->attributes->all())
            );

            return false;
        }

        return true;
    }
}