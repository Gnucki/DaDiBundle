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

/**
 * AbstractYamlFileLoaderDecorator is an absctract class that a decorator 
 * for a yaml file loader should extend.
 *
 * @author Thomas Prelot
 */
abstract class AbstractYamlFileLoaderDecorator implements YamlFileLoaderInterface
{
	/**
	 * The parent of the decorator (see the pattern).
	 *
	 * @var mixed
	 */
	private $parent;

    /**
     * Constructor.
     *
     * @param YamlFileLoader $parent The parent (see pattern).
     */
    public function __construct(YamlFileLoaderInterface $parent)
    {
        $this->setParent($parent);
    }

	/**
     * Get the parent of the decorator (see the pattern).
     *
     * @return YamlFileLoaderInterface The parent.
     */
    public function getParent()
    {
    	return $this->parent;
    }

    /**
     * Set the parent of the decorator (see the pattern).
     *
     * @param YamlFileLoaderInterface $parent The parent.
     */
    protected function setParent(YamlFileLoaderInterface $parent)
    {
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer()
    {
        return $this->getParent()->getContainer();
    }

    /**
     * {@inheritdoc}
     */
    public function getDecoratedInstance()
    {
        return $this->getParent()->getDecoratedInstance();
    }

    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        return $this->getParent()->load($file, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function parseExtraDefinition($id, $service, $file, Definition $definition)
    {
        return $this->getParent()->parseExtraDefinition($id, $service, $file, $definition);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinitionExtra(Definition $definition)
    {
        return $this->getParent()->getDefinitionExtra($definition);
    }

    /**
     * {@inheritdoc}
     */
    public function parseDefinitionAccess($id, $service, $file)
    {
        return $this->getParent()->parseDefinitionAccess($id, $service, $file);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveServicesAccess($value)
    {
        return $this->getParent()->resolveServicesAccess($value);
    }
}