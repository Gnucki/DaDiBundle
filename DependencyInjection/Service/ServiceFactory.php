<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\DependencyInjection\Service;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This definition allows to add extra definition parameters
 * to a definition.
 *
 * @author Thomas Prelot
 */
class ServiceFactory
{
	/**
	 * The identifier of the service of the factory.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * The container.
	 *
	 * @var ContainerInterface
	 */
	private $container;

	/**
     * Constructor.
     *
     * @param string $id The identifier of the service of the factory.
     */
    public function __construct($id, ContainerInterface $container)
    {
        $this->id = $id;
        $this->container = $container;
    }

	/**
	 * Get a service of the factory.
	 *
	 * @param string $id The id of the extra definition.
	 *
	 * @return object The service.
	 */
	public function get($id)
	{
		return $this->container->get($this->id.'.'.$id);
	}
}