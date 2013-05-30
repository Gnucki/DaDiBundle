<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\DependencyInjection\Loader;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * AbstractYamlFileLoaderDecorator is an absctract class that a decorator 
 * for a yaml file loader should extend.
 *
 * @author Thomas Prelot
 */
abstract class AbstractYamlFileLoaderDecorator extends YamlFileLoader
{
	/**
	 * The parent of the decorator (see the pattern).
	 *
	 * @var mixed
	 */
	protected $parent;

    /**
     * Constructor.
     *
     * @param ContainerBuilder     $container A ContainerBuilder instance.
     * @param FileLocatorInterface $locator   A FileLocator instance.
     * @param YamlFileLoader       $parent    The parent (see pattern).
     */
    public function __construct(ContainerBuilder $container, FileLocatorInterface $locator, YamlFileLoader $parent)
    {
        $this->setParent($parent);

        parent::__construct($container, $locator);
    }

	/**
     * Get the parent of the decorator (see the pattern).
     *
     * @return YamlFileLoader The parent.
     */
    public function getParent()
    {
    	return $this->parent;
    }

    /**
     * Set the parent of the decorator (see the pattern).
     *
     * @param YamlFileLoader $parent The parent.
     */
    public function setParent(YamlFileLoader $parent)
    {
        $this->parent = $parent;
    }

    /**
	 * {@inheritdoc}
	 */
    protected function parseExtraDefinition($id, $service, $file, Definition $definition)
    {
        return $this->parent->parseExtraDefinition($id, $service, $file, $definition);
    }
}