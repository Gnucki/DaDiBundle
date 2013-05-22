<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\DefinitionDecorator;

/**
 * This definition allows to add extra definition parameters
 * to a definition.
 *
 * @author Thomas Prelot
 */
class DefinitionDecoratorExtra extends DefinitionDecorator implements DefinitionExtraInterface
{
	/**
	 * The content of the loaded file.
	 *
	 * @var mixed
	 */
	private $extraDefinitions = array();

	/**
     * Constructor.
     *
     * @param Definition $definition The definition to extend.
     */
    public function __construct(Definition $definition = null)
    {
        $this->assimilateDefinition($definition);

        parent::__construct();
    }

    /**
	 * {@inheritdoc}
	 */
    public function assimilateDefinition(Definition $definition)
    {
        $this->setArguments($definition->getArguments());
        $this->setMethodCalls($definition->getMethodCalls());
        $this->setProperties($definition->getProperties());
        $this->setFactoryClass($definition->getFactoryClass());
        $this->setFactoryMethod($definition->getFactoryMethod());
        $this->setFactoryService($definition->getFactoryService());
        $this->setConfigurator($definition->getConfigurator());
        $this->setFile($definition->getFile());
        $this->setPublic($definition->isPublic());
        $this->setAbstract($definition->isAbstract());
        $this->setScope($definition->getScope());
        $this->setTags($definition->getTags());
    }

	/**
	 * {@inheritdoc}
	 */
	public function getExtra($id)
	{
		return (isset($this->extraDefinitions[$id]) ? $this->extraDefinitions[$id] : null);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setExtra($id, ExtraDefinitionInterface $extraDefinition)
	{
		$this->extraDefinitions[$id] = $extraDefinition;
	}
}