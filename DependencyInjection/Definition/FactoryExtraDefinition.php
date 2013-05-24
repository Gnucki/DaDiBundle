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
 * factory to a definition.
 *
 * @author Thomas Prelot
 */
class FactoryExtraDefinition implements ExtraDefinitionInterface
{
	/**
	 * The list of the services.
	 *
	 * @var array
	 */
	private $services = array();

	/**
	 * Add a service.
	 *
	 * @param string $id The id of the service.
	 */
	public function addService($id)
	{
		$this->services[$id] = $id;
	}

	/**
	 * Get the list of the services.
	 *
	 * @return array The services.
	 */
	public function getServices()
	{
		return $this->services;
	}
}