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
 * This definition allows to add extra definition parameters
 * to a definition.
 *
 * @author Thomas Prelot
 */
class InterfaceExtraDefinition implements ExtraDefinitionInterface
{
	/**
	 * The list of the services.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Set the name of the interface.
	 *
	 * @param string $name The name of the interface.
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Get the name of the interface.
	 *
	 * @return array The name of the interface.
	 */
	public function getName()
	{
		return $this->name;
	}
}