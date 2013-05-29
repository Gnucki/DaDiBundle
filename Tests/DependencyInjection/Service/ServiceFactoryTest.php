<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\Tests\DependencyInjection\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Da\DiBundle\DependencyInjection\Service\ServiceFactory;

/**
 * @author Thomas Prelot
 */
class ServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @covers Da\DiBundle\DependencyInjection\Service\ServiceFactory::__construct
	 * @covers Da\DiBundle\DependencyInjection\Service\ServiceFactory::get
	 */
	public function testGet()
	{
		$container = new ContainerBuilder();
        $container->register('service.factory.a', 'Da\DiBundle\Tests\Fixtures\Service\Service');

		$factory = new ServiceFactory('service.factory', $container);
		$this->assertEquals($container->get('service.factory.a'), $factory->get('a'), '->get() returns a manufactured service of the factory');
	}
}