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

/**
 * This definition allows to add the extra definition parameters
 * builder to a definition.
 *
 * @author Thomas Prelot
 */
class BuilderExtraDefinition implements ExtraDefinitionInterface
{
	/**
	 * The class of the builder.
	 *
	 * @var string
	 */
	private $class;
    
    /**
	 * The method of the builder.
	 *
	 * @var string
	 */
    private $method = 'build';
    
	/**
	 * The service of the builder.
	 *
	 * @var string
	 */
    private $service;

	/**
	 * Set the class of the builder.
	 *
	 * @param string $class The class of the builder.
	 */
	public function setClass($class)
	{
		$this->class = $class;
	}

	/**
	 * Get the class of the builder.
	 *
	 * @return array The class of the builder.
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * Set the method of the builder.
	 *
	 * @param string $method The method of the builder.
	 */
	public function setMethod($method)
	{
		$this->method = $method;
	}

	/**
	 * Get the method of the builder.
	 *
	 * @return array The method of the builder.
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Set the service of the builder.
	 *
	 * @param string $service The service of the builder.
	 */
	public function setService($service)
	{
		$this->service = $service;
	}

	/**
	 * Get the service of the builder.
	 *
	 * @return array The service of the builder.
	 */
	public function getService()
	{
		return $this->service;
	}
}