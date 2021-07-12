<?php

namespace Gta\TracabiliteBundle\Formatter;

use Gta\CoreBundle\DataBase\DbConnectionAwareInterface;
use Gta\CoreBundle\DataBase\DbConnectionTrait;
use Gta\CoreBundle\Expression\GtaExpressionLanguage;
use Gta\TracabiliteBundle\Entity\EgmhistLogObject;
use Gta\TracabiliteBundle\Exception\MessageDesciptionGenerationException;
use Gta\TracabiliteBundle\Manager\TracabiliteConfig;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

/**
 * Class AbstractTracabiliteFormatter
 *
 * @package Gta\TracabiliteBundle\Entity
 * @author  Seif <ben.s@mipih.fr>
 */
abstract class AbstractTracabiliteFormatter implements DbConnectionAwareInterface
{
    use DbConnectionTrait;

    /**
     * @var ExpressionLanguage
     */
    public $expressionLanguage;
    /**
     * @var \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     */
    private $dbEgmhistLogObject;

    /**
     * AbstractTracabiliteFormatter constructor.
     *
     * @param \Gta\CoreBundle\Expression\GtaExpressionLanguage $expressionLanguage
     */
    final public function __construct(GtaExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * Formater les données afin de les rendre prêtes pour une insertion dans la BD (EGMHIST)
     *
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject |
     *                                                          \Gta\TracabiliteBundle\Entity\DbEgmhistLogObjectCollection
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function format();

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function generateMessage();

    /**
     * @param $trigger
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function supports($trigger);

    /**
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     *
     * @author Seif <ben.s@mipih.fr>
     */
    final public function getDbEgmhistLogObject()
    {
        return $this->dbEgmhistLogObject;
    }

    /**
     * @param \Gta\TracabiliteBundle\Entity\EgmhistLogObject $dbEgmhistLogObject
     *
     * @return \Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter
     * @author Seif <ben.s@mipih.fr>
     */
    final public function setDbEgmhistLogObject(EgmhistLogObject $dbEgmhistLogObject)
    {
        $this->dbEgmhistLogObject = $dbEgmhistLogObject;

        return $this;
    }

    /**
     * @return string
     * @throws \Gta\TracabiliteBundle\Exception\MessageDesciptionGenerationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    protected function generateLibAction()
    {
        $codAct = $this->getDbEgmhistLogObject()->getCodAct();
        $template = TracabiliteConfig::getMsgTemplate($codAct);
        $params = $this->getDbEgmhistLogObject()->getUcParams();
        if (!$template) {
            return '__PAS_DE_MSG_PREFORMATE__';
        }
        try {
            return $this->expressionLanguage->evaluate($template, $params);
        } catch (SyntaxError $e) {
            throw new MessageDesciptionGenerationException($e->getMessage(), $params, $template);
        } catch(\RuntimeException $e) {
            return '_';
        }
    }
}