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
use Da\DiBundle\DependencyInjection\Compiler\CheckInterfaceValidityPass;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtra;
use Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition;

/**
 * @author Thomas Prelot
 */
class CheckInterfaceValidityPassTest extends \PHPUnit_Framework_TestCase
{
    public function getContainer()
    {
        $container = new ContainerBuilder();
        $container->register('a', 'Class');
        $container->register('b', 'Class');

        $interfaceExtraDefinition = new InterfaceExtraDefinition();
        $interfaceExtraDefinition->setName('Da\DiBundle\Tests\Fixtures\Compiler\ServiceInterface');
        $definition = new DefinitionExtra();
        $definition->setClass('Da\DiBundle\Tests\Fixtures\Compiler\InterfacedService');
        $definition->setExtra('interface', $interfaceExtraDefinition);
        $container->setDefinition('c', $definition);

        $container->register('d', 'Class');

        return array(array($container));
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Compiler\CheckInterfaceValidityPass::process
     * @covers Da\DiBundle\DependencyInjection\Compiler\CheckInterfaceValidityPass::checkDefinition
     * @dataProvider getContainer
     */
    public function testProcessOk(ContainerBuilder $container)
    {
        $this->process($container);
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Compiler\CheckInterfaceValidityPass::process
     * @covers Da\DiBundle\DependencyInjection\Compiler\CheckInterfaceValidityPass::checkDefinition
     * @dataProvider getContainer
     * @expectedException Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function testProcessNonExistentInterface(ContainerBuilder $container)
    {
        $interfaceExtraDefinition = new InterfaceExtraDefinition();
        $interfaceExtraDefinition->setName('NonExistentInterface');
        $definition = new DefinitionExtra();
        $definition->setClass('Da\DiBundle\Tests\Fixtures\Compiler\InterfacedService');
        $definition->setExtra('interface', $interfaceExtraDefinition);
        $container->setDefinition('nonExistentInterfaceService', $definition);

        $this->process($container);
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Compiler\CheckInterfaceValidityPass::process
     * @covers Da\DiBundle\DependencyInjection\Compiler\CheckInterfaceValidityPass::checkDefinition
     * @dataProvider getContainer
     * @expectedException Symfony\Component\DependencyInjection\Exception\RuntimeException
     */
    public function testProcessNonImplementedInterface(ContainerBuilder $container)
    {
        $interfaceExtraDefinition = new InterfaceExtraDefinition();
        $interfaceExtraDefinition->setName('Da\DiBundle\Tests\Fixtures\Compiler\ServiceInterface');
        $definition = new DefinitionExtra();
        $definition->setClass('Da\DiBundle\Tests\Fixtures\Compiler\NonInterfacedService');
        $definition->setExtra('interface', $interfaceExtraDefinition);
        $container->setDefinition('nonImplementedInterfaceService', $definition);

        $this->process($container);
    }

    protected function process(ContainerBuilder $container)
    {
        $compiler = new CheckInterfaceValidityPass();
        $compiler->process($container);
    }
}
