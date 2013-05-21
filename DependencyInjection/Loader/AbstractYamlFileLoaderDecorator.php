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
abstract class AbstractYamlFileLoaderDecorator extends YamlFileLoader
{
	/**
	 * The parent of the decorator (see the pattern).
	 *
	 * @var mixed
	 */
	protected $parent;

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
    protected function parseAdditionalDefinition($id, $service, $file, Definition $definition)
    {
        return $this->parent()->parseAdditionalDefinition($id, $service, $file, $definition);
    }
}