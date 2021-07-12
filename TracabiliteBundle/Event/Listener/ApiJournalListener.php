<?php

namespace Gta\TracabiliteBundle\Event\Listener;

use Gta\CoreBundle\Log\HasLoggingFeatureInterface;
use Gta\CoreBundle\Log\LoggingFeatureTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Description of ResponseListener
 *
 * @author Seif <ben.s@mipih.fr> 22 janv. 2018 15:11:09
 */
class ApiJournalListener implements HasLoggingFeatureInterface
{
    use LoggingFeatureTrait;
    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    protected $logger;


    /**
     * ResponseListener constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Traitement suite au déclenchement de l'évènement
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->isLoggingDisabled()) {
            // $event->stopPropagation();
            // un 08/01/2019 toute une journée à cause de cette putain d'instruction
            // un démo a été modifié et une réunion reportée

            return;
        }
        $request = $event->getRequest();
        $attributes = $request->attributes;
        $context = [
            'request_date'         => date("d/m/Y H:i:s"),
            'request_query_params' => $attributes->get('_route_params'),
            'route_name'           => $attributes->get('_route'),
            'request_action_name'  => $attributes->get('_controller'),
        ];
        $this->logger->log(Logger::INFO, 'Opération faite avec succès', $context);
    }


}