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
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * DefinitionExtraImplementor is the class that contains the implementation
 * for the objects implementing the DefinitionExtraInterface interface.
 *
 * @author Thomas Prelot
 */
class DefinitionExtraImplementor implements DefinitionExtraInterface
{
	/**
	 * The abstracted definition (see bridge pattern).
	 *
	 * @var DefinitionExtraInterface
	 */
	private $abstraction;

	/**
	 * The content of the loaded file.
	 *
	 * @var mixed
	 */
	private $extraDefinitions = array();

	/**
     * Constructor.
     *
     * @param DefinitionExtraInterface $abstraction The abstracted definition.
     */
    public function __construct(DefinitionExtraInterface $abstraction)
    {
    	$this->abstraction = $abstraction;
    }

    /**
	 * {@inheritdoc}
	 */
    public function assimilateDefinition(Definition $definition)
    {
    	$this->abstraction->setClass($definition->getClass());
        $this->abstraction->setArguments($definition->getArguments());
        $this->abstraction->setMethodCalls($definition->getMethodCalls());
        $this->abstraction->setProperties($definition->getProperties());
        $this->abstraction->setFactoryClass($definition->getFactoryClass());
        $this->abstraction->setFactoryMethod($definition->getFactoryMethod());
        $this->abstraction->setFactoryService($definition->getFactoryService());
        $this->abstraction->setConfigurator($definition->getConfigurator());
        $this->abstraction->setFile($definition->getFile());
        $this->abstraction->setPublic($definition->isPublic());
        $this->abstraction->setAbstract($definition->isAbstract());
        $this->abstraction->setScope($definition->getScope());
        $this->abstraction->setTags($definition->getTags());
        if ($definition instanceof DefinitionExtraInterface)
            $this->abstraction->setExtras($definition->getExtras());
    }

    /**
	 * Get the abstracted definition.
	 *
	 * @return DefinitionExtraInterface The abstracted definition.
	 */
	public function getAbstraction()
	{
		return $this->abstraction;
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

	/**
	 * {@inheritdoc}
	 */
	public function unsetExtra($id)
	{
		unset($this->extraDefinitions[$id]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getExtras()
	{
		return $this->extraDefinitions;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setExtras(array $extraDefinitions)
	{
		foreach ($extraDefinitions as $extraDefinition)
		{
			if (!($extraDefinition instanceof ExtraDefinitionInterface))
				throw new InvalidArgumentException('The elements of the $extraDefinitions array should implement the ExtraDefinitionInterface interface.');
		}
		$this->extraDefinitions = $extraDefinitions;
	}
}