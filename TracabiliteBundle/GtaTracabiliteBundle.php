<?php

namespace Gta\TracabiliteBundle;

use Gta\TracabiliteBundle\DependencyInjection\Compiler\ExpressionProvidersRegisterPass;
use Gta\TracabiliteBundle\DependencyInjection\Compiler\StrategyFormattersRegisterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class GtaTracabiliteBundle
 *
 * @package Gta\TracabiliteBundle
 * @author  Seif <ben.s@mipih.fr>
 * @version 19
 */
class GtaTracabiliteBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function build(Containerbuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StrategyFormattersRegisterPass());
    }
}
