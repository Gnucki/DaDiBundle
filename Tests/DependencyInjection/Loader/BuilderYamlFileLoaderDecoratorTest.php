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
use Da\DiBundle\DependencyInjection\Loader\BuilderYamlFileLoaderDecorator;

/**
 * @author Thomas Prelot
 */
class BuilderYamlFileLoaderDecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function getService()
    {
        return array
            (
                array(array('builder' => array('service' => 'builder', 'method' => 'get', 'class' => 'Class'))),
                array(array('builder' => 'builder'))
            );
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Loader\BuilderYamlFileLoaderDecorator::parseExtraDefinition
     * @dataProvider getService
     * @runInSeparateProcess
     */
    public function testParseExtraDefinition($service)
    {
        YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\BuilderYamlFileLoaderDecorator');
        $container = new ContainerBuilder();
        $loader = YamlFileLoader::decorate($container, new FileLocator(__DIR__.'/../../Fixtures/Loader'));
        $definition = new Definition('Class');
        $container->setDefinition('service', $definition);
        $definition = $loader->parseExtraDefinition('service', $service, 'dumbFile', $definition);

        $this->assertNotNull($definition->getExtra('builder'), '->parseExtraDefinition() adds an extra definition "builder"');
        if (isset($service['builder']['service']))
            $this->assertEquals($service['builder']['service'], $definition->getExtra('builder')->getService(), '->parseExtraDefinition() sets the service of the builder in the definition');
        else
            $this->assertEquals($service['builder'], $definition->getExtra('builder')->getService(), '->parseExtraDefinition() sets the service of the builder in the definition');
    }
}