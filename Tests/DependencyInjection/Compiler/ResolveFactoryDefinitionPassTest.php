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
use Da\DiBundle\DependencyInjection\Compiler\ResolveFactoryDefinitionPass;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtra;
use Da\DiBundle\DependencyInjection\Definition\FactoryExtraDefinition;
use Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition;

/**
 * @author Thomas Prelot
 */
class ResolveFactoryDefinitionPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Da\DiBundle\DependencyInjection\Compiler\ResolveFactoryDefinitionPass::process
     * @covers Da\DiBundle\DependencyInjection\Compiler\ResolveFactoryDefinitionPass::resolveDefinition
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->register('factory.service.a', 'Class');
        $interfaceExtraDefinition = new InterfaceExtraDefinition();
        $interfaceExtraDefinition->setName('SpecificInterface');
        $definition = new DefinitionExtra();
        $definition->setFactoryMethod('get');
        $definition->setExtra('interface', $interfaceExtraDefinition);
        $container->setDefinition('factory.service.b', $definition);

        $container->register('factory.service.c', 'Class');
        $container->register('d', 'Class');

        $factoryExtraDefinition = new FactoryExtraDefinition();
        $factoryExtraDefinition->addService('factory.service.a');
        $factoryExtraDefinition->addService('factory.service.b');
        $factoryExtraDefinition->addService('factory.service.c');
        $interfaceExtraDefinition = new InterfaceExtraDefinition();
        $interfaceExtraDefinition->setName('Interface');
        $definition = new DefinitionExtra();
        $definition->setFactoryMethod('build');
        $definition->setExtra('interface', $interfaceExtraDefinition);
        $definition->setExtra('factory', $factoryExtraDefinition);
        $container->setDefinition('factory.service', $definition);

        $compiler = new ResolveFactoryDefinitionPass();
        $compiler->process($container);

        $definition = $container->getDefinition('factory.service');
        $this->assertEquals('Da\DiBundle\DependencyInjection\Service\ServiceFactory', $definition->getClass(), '->process() set the factory service as an instance of "Da\DiBundle\DependencyInjection\Service\ServiceFactory"');
        $this->assertTrue($definition->isPublic(), '->process() set the factory service as public');
        $this->assertEquals('', $definition->getFactoryMethod(), '->process() copies the factory service parameters to its manufactured services and reset them');
    
        $definition = $container->getDefinition('factory.service.a');
        $this->assertEquals('build', $definition->getFactoryMethod(), '->process() copies the parameters of the factory service to its manufactured services');
        $this->assertEquals('Interface', $definition->getExtra('interface')->getName(), '->process() copies the extra parameters of the factory service to its manufactured services');
        $this->assertNull($definition->getExtra('factory'), '->process() does not copy the extra factory parameter of the factory service to its manufactured services');

        $definition = $container->getDefinition('factory.service.b');
        $this->assertEquals('get', $definition->getFactoryMethod(), '->process() overrides the parameters of the factory service with the specific parameters of manufacted serives');
        $this->assertEquals('SpecificInterface', $definition->getExtra('interface')->getName(), '->process() overrides the extra parameters of the factory service with the specific parameters of manufacted serives');
    }
}
