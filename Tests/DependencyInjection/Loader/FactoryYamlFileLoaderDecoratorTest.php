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
use Da\DiBundle\DependencyInjection\Loader\FactoryYamlFileLoaderDecorator;

/**
 * @author Thomas Prelot
 */
class FactoryYamlFileLoaderDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Da\DiBundle\DependencyInjection\Loader\FactoryYamlFileLoaderDecorator::parseExtraDefinition
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::parseExtraDefinition
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::getDecoratedInstance
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::getContainer
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::getDefinitionExtra
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::parseDefinitionAccess
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::resolveServicesAccess
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::getDecoratedInstance
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::getContainer
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::getDefinitionExtra
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::parseDefinitionAccess
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::DUPLICATED_parseDefinition
     * @runInSeparateProcess
     */
    public function testParseExtraDefinition()
    {
        YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\FactoryYamlFileLoaderDecorator');
        $container = new ContainerBuilder();
        $loader = YamlFileLoader::decorate($container, new FileLocator(__DIR__.'/../../Fixtures/Loader'));
        $service = array
            (
                'factory' => array('a' => array(), 'b' => array())
            );
        $definition = new Definition('Class');
        $container->setDefinition('service', $definition);
        $definition = $loader->parseExtraDefinition('service', $service, 'dumbFile', $definition);

        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Definition\DefinitionExtra', $container->getDefinition('service.a'), '->parseExtraDefinition() creates a DefinitionExtra for the manufactured services');
        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Definition\DefinitionExtra', $definition, '->parseExtraDefinition() transforms the definition in a DefinitionExtra instance');
        $this->assertNotNull($definition->getExtra('factory'), '->parseExtraDefinition() adds an extra definition "factory"');
    }
}