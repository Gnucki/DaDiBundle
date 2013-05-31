<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\Tests\DependencyInjection\Loader;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Da\DiBundle\DependencyInjection\Loader\YamlFileLoader;
use Da\DiBundle\DependencyInjection\Loader\InterfaceYamlFileLoaderDecorator;

/**
 * @author Thomas Prelot
 */
class InterfaceYamlFileLoaderDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Da\DiBundle\DependencyInjection\Loader\InterfaceYamlFileLoaderDecorator::parseExtraDefinition
     * @runInSeparateProcess
     */
    public function testParseExtraDefinition()
    {
        YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\InterfaceYamlFileLoaderDecorator');
        $container = new ContainerBuilder();
        $loader = YamlFileLoader::decorate($container, new FileLocator(__DIR__.'/../../Fixtures/Loader'));
        $service = array
            (
                'interface' => 'Interface'
            );
        $definition = new Definition('Class');
        $container->setDefinition('service', $definition);
        $definition = $loader->parseExtraDefinition('service', $service, 'dumbFile', $definition);

        $this->assertNotNull($definition->getExtra('interface'), '->parseExtraDefinition() adds an extra definition "interface"');
        $this->assertEquals($service['interface'], $definition->getExtra('interface')->getName(), '->parseExtraDefinition() sets the name of the interface in the definition');
    }
}