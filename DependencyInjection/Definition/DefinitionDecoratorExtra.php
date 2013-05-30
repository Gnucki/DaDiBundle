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

use Symfony\Component\DependencyInjection\Definition;
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
     * The implementor (see bridge pattern).
     *
     * @var DefinitionExtraImplementor
     */
    private $implementor = array();
    
	/**
     * Constructor.
     *
     * @param string $parent The id of Definition instance to decorate.
     * @param Definition $definition The definition to extend.
     */
    public function __construct(DefinitionDecorator $definition)
    {
        $this->implementor = new DefinitionExtraImplementor($this);

        parent::__construct($definition->getParent());
        $this->assimilateDefinition($definition);
    }

    /**
	 * {@inheritdoc}
	 */
    public function assimilateDefinition(Definition $definition)
    {
        $this->implementor->assimilateDefinition($definition);
    }

	/**
	 * {@inheritdoc}
	 */
	public function getExtra($id)
	{
        return $this->implementor->getExtra($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setExtra($id, ExtraDefinitionInterface $extraDefinition)
	{
        $this->implementor->setExtra($id, $extraDefinition);
	}

    /**
     * {@inheritdoc}
     */
    public function unsetExtra($id)
    {
        $this->implementor->unsetExtra($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtras()
    {
        return $this->implementor->getExtras();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtras(array $extraDefinitions)
    {
        $this->implementor->setExtras($extraDefinitions);
    }
}