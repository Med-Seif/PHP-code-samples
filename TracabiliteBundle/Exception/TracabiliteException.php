<?php

namespace Gta\TracabiliteBundle\Exception;

use Gta\CoreBundle\Exception\GtaException;
use Gta\Domain\Lib\Std;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants;
use Throwable;

/**
 * Class AbstractTracabiliteException
 *
 * @package Gta\TracabiliteBundle\Exception
 * @author  Seif <ben.s@mipih.fr>
 */
class TracabiliteException extends GtaException
{
    private static $currentTrigger;
    private static $currentFormatter;
    private static $currentParams;

    /**
     * AbstractTracabiliteException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     *
     * @throws \ReflectionException
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        $trigger = self::getCurrentTrigger();
        $formatter = self::getCurrentFormatter();
        // récupérer le nom de la constante (plus significative) au lieu de code action
        $codActConstantName = Std::getConstantName(TracabiliteConstants::class, $trigger);
        $message .= <<<EOT

Current codAct is : $codActConstantName = '$trigger'
Current formatter is : $formatter
EOT;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public static function getCurrentTrigger()
    {
        return self::$currentTrigger;
    }

    /**
     * @param mixed $currentTrigger
     */
    public static function setCurrentTrigger($currentTrigger)
    {
        self::$currentTrigger = $currentTrigger;
    }

    /**
     * @return mixed
     */
    public static function getCurrentFormatter()
    {
        return self::$currentFormatter;
    }

    /**
     * @param mixed $currentFormatter
     */
    public static function setCurrentFormatter($currentFormatter)
    {
        self::$currentFormatter = $currentFormatter;
    }

    /**
     * @return mixed
     */
    public static function getCurrentParams()
    {
        return self::$currentParams;
    }

    /**
     * @param mixed $currentParams
     */
    public static function setCurrentParams($currentParams)
    {
        self::$currentParams = $currentParams;
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public static function resetCurrentFormatter()
    {
        self::$currentFormatter = null;
    }

}