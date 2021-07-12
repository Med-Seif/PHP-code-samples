<?php

namespace Gta\TracabiliteBundle\Log\Formatter;

use Gta\TracabiliteBundle\Entity\EgmhistLogObject;
use Gta\TracabiliteBundle\Entity\EgmhistLogObjectCollection;
use Gta\TracabiliteBundle\Exception\TracabiliteException;
use Gta\TracabiliteBundle\Exception\TriggerHasNoFormatterException;
use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class EgmhistLogFormatter
 *
 * @package Gta\TracabiliteBundle\Log\Formatter
 * @author  Seif <ben.s@mipih.fr>
 */
class EgmhistLogFormatter extends AbstractDbTableLogFormatter implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var array Un tableau de booleans indéxés par clés de configuration ou chaque
     * clef représente un formatter, définis lors de la phase de compilation
     */
    private $strategiesList = [];

    /**
     * Description
     *
     * @param array $record
     *
     * @return bool|array
     * @throws \Gta\TracabiliteBundle\Exception\TriggerHasNoFormatterException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function format(array $record)
    {
        $this->logger->log(Logger::DEBUG, 'Formatting process start', [__CLASS__, __FUNCTION__]);
        // données logger vides
        if (0 === count($record)) {
            return false;
        }
        $trigger = $record['context']['trigger'];
        $this->logger->log(Logger::DEBUG, 'Current trigger is ' . $trigger);

        $dbEgmhistLogObject = $this->getSerializer()->denormalize($record, EgmhistLogObject::class);
        $this->logger->log(Logger::DEBUG, 'Denoramlization result object class is '.get_class($dbEgmhistLogObject));

        $formatter = $this->getFormatter($trigger);
        TracabiliteException::setCurrentFormatter(get_class($formatter));
        $this->logger->log(Logger::DEBUG, 'Voted formatter class is '.get_class($formatter));

        // vérification blackList
        if ($this->isDisabled(get_class($formatter))) {
            $this->logger->log(Logger::DEBUG, '>>>>>>>>>>>>>>>>>>>>>Formatter is DISABLED ', [get_class($formatter)]);

            return false;
        }
        // configuration formatter
        $formattedDbEgmhistLogObject = $this->getFormattedLogObject($formatter, $dbEgmhistLogObject);

        $formattedArray = $this->getSerializer()->normalize($formattedDbEgmhistLogObject);
        $this->logger->log(Logger::DEBUG, 'Normalization array result', $formattedArray);

        return $formattedArray; // retourner la clef 'formatted' du tableau $record dans le Handler
    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function formatBatch(array $records)
    {
        return $records;
    }

    /**
     * @param array $strategiesList
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function setStrategiesList(array $strategiesList)
    {
        foreach ($strategiesList as $id => $elem) {
            $newID = str_replace('_', '', strtolower($id));
            $this->strategiesList[$newID] = $elem;
        }
    }

    /**
     * @param $formatter
     * @param $dbEgmhistLogObject
     *
     * @return EgmhistLogObject
     *@author Seif <ben.s@mipih.fr>
     */
    private function getFormattedLogObject(
        AbstractTracabiliteFormatter $formatter,
        EgmhistLogObject $dbEgmhistLogObject
    )
    {
        $formatted = $formatter
            ->setDbEgmhistLogObject($dbEgmhistLogObject)
            ->format();

        if ($formatted instanceof EgmhistLogObjectCollection) {
            return $formatted; // pas de génération de message pour les générateurs de collections (multi insert, voir déplacements)
        }
        $message = $formatter->generateMessage();
        $formattedDbEgmhistLogObject = $formatter->getDbEgmhistLogObject()->setMessage($message);
        // En attendant le PHP 7 ! :~(
        // à voir si on doit implémenter la vérification de type de l'objet aussi, ou pas
        if (null === $formattedDbEgmhistLogObject || !is_object($formattedDbEgmhistLogObject)) {
            throw new \LogicException(get_class($formatter) . '::format() must return a not null value');
        }

        return $formattedDbEgmhistLogObject;
    }

    /**
     * Description
     *
     * @param $fcqnFormatterClassName
     *
     * @return bool
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    private function isDisabled($fcqnFormatterClassName)
    {
        $formatterShortClassName = (new \ReflectionClass($fcqnFormatterClassName))->getShortName();
        // convertir de "FicheFormatter" à "fiche", pour matcher les clés de configuration
        $formatterID = strtolower(
            substr(
                $formatterShortClassName,
                0,
                strpos($formatterShortClassName, 'Formatter')
            )
        );
        // pour chaque formatteur crée, il faut lui associer une clef de configuration
        // todo: chargement dynamique des formateurs à partir du répertoire(dans l'extension même)
        if (!array_key_exists($formatterID, $this->strategiesList)) {
            throw new \LogicException('You should add a config key for ' . $formatterID);
        }

        // existe dans la config et vaut FALSE || n'existe pas dans la config (par défaut à false)
        return (false === $this->strategiesList[$formatterID]);
    }

    /**
     * @param $trigger
     *
     * @return \Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter
     * @throws \Gta\TracabiliteBundle\Exception\TriggerHasNoFormatterException
     * @author Seif <ben.s@mipih.fr>
     */
    private function getFormatter($trigger)
    {
        foreach ($this->getSubscribedFormattingStrategies() as $strategy) {
            if (true === $strategy->supports($trigger)) {
                return $strategy;
            }
        }

        throw new TriggerHasNoFormatterException($trigger);
    }
}