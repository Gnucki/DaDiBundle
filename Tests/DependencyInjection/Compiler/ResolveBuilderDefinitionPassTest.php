<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Da\DiBundle\DependencyInjection\Compiler\ResolveBuilderDefinitionPass;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtra;
use Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition;

/**
 * @author Thomas Prelot
 */
class ResolveBuilderDefinitionPassTest extends \PHPUnit_Framework_TestCase
{
    public function getBuilder()
    {
        return array
            (
                array('BuilderClass', 'get', '', 'BuilderClass', 'get', ''),
                array('', 'get', 'builder.service', '', 'get', 'builder.service'),
                array('BuilderClass', '', '', 'BuilderClass', 'build', ''),
                array('', '', 'builder.service', '', 'build', 'builder.service'),
            );
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Compiler\ResolveBuilderDefinitionPass::process
     * @covers Da\DiBundle\DependencyInjection\Compiler\ResolveBuilderDefinitionPass::resolveDefinition
     * @dataProvider getBuilder
     */
    public function testProcess($class, $method, $service, $expectedClass, $expectedMethod, $expectedService)
    {
        $container = new ContainerBuilder();

        $builderExtraDefinition = new BuilderExtraDefinition();
        if (!empty($class))
            $builderExtraDefinition->setClass($class);
        if (!empty($method))
            $builderExtraDefinition->setMethod($method);
        if (!empty($service))
            $builderExtraDefinition->setService($service);
        $definition = new DefinitionExtra();
        $definition->setExtra('builder', $builderExtraDefinition);
        $container->setDefinition('builded.service', $definition);

        $compiler = new ResolveBuilderDefinitionPass();
        $compiler->process($container);

        $definition = $container->getDefinition('builded.service');
        $this->assertEquals($expectedClass, $definition->getFactoryClass(), '->process() copies the builder class into the parameter "factory_class"');
        $this->assertEquals($expectedMethod, $definition->getFactoryMethod(), '->process() copies the builder method into the parameter "factory_method" or is "build" by default');
        $this->assertEquals($expectedService, $definition->getFactoryService(), '->process() copies the builder class into the parameter "factory_service"');
    }
}
