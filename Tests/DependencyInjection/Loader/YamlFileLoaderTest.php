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
use Symfony\Component\Config\FileLocator;
use Da\DiBundle\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Thomas Prelot
 */
class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    private static $loader;
    private static $container;

    public function getDecoratedLoader()
    {
        if (!self::$loader)
        {
            YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\FactoryYamlFileLoaderDecorator');
            YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\BuilderYamlFileLoaderDecorator');
            YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\InterfaceYamlFileLoaderDecorator');
            self::$container = new ContainerBuilder();
            self::$loader = YamlFileLoader::decorate(self::$container, new FileLocator(__DIR__.'/../../Fixtures/Loader'));
        }
        return self::$loader;
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::addDecorator
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::decorate
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::__construct
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::setParent
     * @covers Da\DiBundle\DependencyInjection\Loader\AbstractYamlFileLoaderDecorator::getParent
     */
    public function testDecorate()
    {
        $loader = $this->getDecoratedLoader();
        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Loader\FactoryYamlFileLoaderDecorator', $loader);
        
        $loader = $loader->getParent();
        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Loader\BuilderYamlFileLoaderDecorator', $loader);

        $loader = $loader->getParent();
        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Loader\InterfaceYamlFileLoaderDecorator', $loader);

        $loader = $loader->getParent();
        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Loader\YamlFileLoader', $loader);
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::parseExtraDefinitions
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::parseExtraDefinition
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::load
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::loadFile
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::getDefinitionExtra
     * @covers Da\DiBundle\DependencyInjection\Loader\YamlFileLoader::DUPLICATED_resolveServices
     */
    public function testParseExtraDefinitions()
    {
        $loader = $this->getDecoratedLoader();
        $loader->load('services.yml');

        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Definition\DefinitionExtra', self::$container->getDefinition('da.test'));
        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra', self::$container->getDefinition('da.test.child'));
    }
}