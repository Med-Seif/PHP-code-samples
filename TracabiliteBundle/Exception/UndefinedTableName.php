<?php

namespace Gta\TracabiliteBundle\Exception;

/**
 * Class UndefinedTableName
 *
 * @package Gta\TracabiliteBundle\Exception
 * @author  Seif <ben.s@mipih.fr>
 */
class UndefinedTableName extends TracabiliteException
{
    const MESSAGE = 'Vous devez définir un nom de table valide pour écriture des logs';
}