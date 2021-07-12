<?php

namespace Gta\TracabiliteBundle\Event\Listener;

use Gta\TracabiliteBundle\Event\Event\EgmhistEvent;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class DbCrudListener
 *
 * @author  Seif <ben.s@mipih.fr>
 */
class EgmhistListener implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $loggerCrud;

    /**
     * DbCrudListener constructor.
     *
     * @param \Psr\Log\LoggerInterface $loggerCrud
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function __construct(LoggerInterface $loggerCrud)
    {
        $this->loggerCrud = $loggerCrud;
    }

    /**
     * Traitement suite au déclenchement de l'évènement
     *
     * @param \Gta\TracabiliteBundle\Event\Event\EgmhistEvent $event
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function onCrud(EgmhistEvent $event)
    {
        # logging "tracabilité" opérations see "/var/logs/medical"
        $this->logger->log(Logger::DEBUG, 'Event dispatched success, and logging process start', [__CLASS__, __FUNCTION__]);
        # logging the real "tracabilté" in EGMHIST
        $this->loggerCrud->log(Logger::INFO, 'Opération faite avec succès', $event->toArray());
    }


}