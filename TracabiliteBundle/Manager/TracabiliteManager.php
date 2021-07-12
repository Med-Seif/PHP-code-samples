<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 06/03/2019 18:27
 */

namespace Gta\TracabiliteBundle\Manager;


use Gta\CoreBundle\Event\Event\EventManagerAwareInterface;
use Gta\CoreBundle\Event\EventManagerTrait;
use Gta\TracabiliteBundle\Event\Event\EgmhistEvent;
use Gta\TracabiliteBundle\Exception\TracabiliteException;
use Gta\CoreBundle\Log\LoggingFeatureTrait;
use Gta\TracabiliteBundle\Exception\MissingParamsException;
use Gta\TracabiliteBundle\Exception\MissingTriggerConfigurationException;
use Gta\TracabiliteBundle\Exception\MissingTriggerException;
use Gta\TracabiliteBundle\Exception\UndefinedCodeActiviteException;
use Gta\TracabiliteBundle\Exception\UndefinedCodeFonctionnaliteException;
use Gta\CoreBundle\Log\HasLoggingFeatureInterface;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class TracabiliteManager
 *
 * @package Gta\TracabiliteBundle\Manager
 * @author  Seif <ben.s@mipih.fr>
 * @version 19
 */
class TracabiliteManager implements EventManagerAwareInterface, HasLoggingFeatureInterface, LoggerAwareInterface
{
    use EventManagerTrait,
        LoggingFeatureTrait,
        LoggerAwareTrait;

    /**
     * Dispatches a db.crud event
     * Use this for manual trigger
     *
     * @param string     $trigger
     * @param array      $params
     * @param null|array $extraFormatterParams
     *
     * @return \Gta\TracabiliteBundle\Manager\TracabiliteManager
     * @throws \Gta\TracabiliteBundle\Exception\MissingParamsException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTriggerConfigurationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTriggerException
     * @throws \Gta\TracabiliteBundle\Exception\UndefinedCodeFonctionnaliteException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function tracabiliteDispatch(
        $trigger,
        array $params,
        $extraFormatterParams = null
    ) {
        $this->logger->log(Logger::DEBUG, '-------------- Start --------------', array($trigger));
        // on a désactivé la tracabilité entièrement
        // je place cela tout au début pour fournir un moyen rapide
        // de désactiver en cas de problèmes (voir code qui suit)
        if ($this->isLoggingDisabled()) {
            $this->logger->log(Logger::DEBUG, 'Logging is disabled');

            return;
        }
        TracabiliteException::resetCurrentFormatter();
        TracabiliteException::setCurrentTrigger($trigger);
        TracabiliteException::setCurrentParams($params);
        // pas de trigger fourni
        if (null === $trigger || 0 === strlen(strval($trigger))) {
            throw new MissingTriggerException();
        }

        // tableau de paramètres vide
        if (0 === count($params)) {
            throw new MissingParamsException();
        }

        // trigger non configuré dans la classe TracabiliteConfig en l'attachant à une fonctionnalité
        if (false === TracabiliteConfig::isConfiguredTrigger($trigger)) {
            throw new MissingTriggerConfigurationException($trigger);
        }

        // pas de codFct pour le trigger fourni
        if (null === $codFct = TracabiliteConfig::getCodFct($trigger)) {
            throw new UndefinedCodeFonctionnaliteException($trigger);
        }

        $this->logger->log(Logger::DEBUG, 'Dispatch Event');
        // go !
        $this->getEventDispatcher()->dispatch(
            EgmhistEvent::NAME,
            new EgmhistEvent(
                $trigger,
                $trigger,
                $codFct,
                $params,
                $extraFormatterParams
            )
        );
        $this->logger->log(Logger::DEBUG, '-------------- End --------------');

        return $this;
    }


}