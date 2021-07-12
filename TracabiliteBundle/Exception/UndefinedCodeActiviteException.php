<?php

namespace Gta\TracabiliteBundle\Exception;

/**
 * Class UndefinedCodeActiviteException
 *
 * @package Gta\TracabiliteBundle\Exception
 * @author  Seif <ben.s@mipih.fr>
 */
class UndefinedCodeActiviteException extends TracabiliteException
{
    /**
     * UndefinedCodeActiviteException constructor.
     *
     * @param string $dbObjectTrigger
     *
     * @throws \ReflectionException
     */
    public function __construct($dbObjectTrigger)
    {
        parent::__construct($dbObjectTrigger, 0, null);
    }
}