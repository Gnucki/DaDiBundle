<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\Tests\DependencyInjection\Definition;

use Da\DiBundle\DependencyInjection\Definition\FactoryExtraDefinition;

/**
 * @author Thomas Prelot
 */
class FactoryExtraDefinitionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\FactoryExtraDefinition::getServices
	 * @covers Da\DiBundle\DependencyInjection\Definition\FactoryExtraDefinition::addService
	 */
	public function testGetAddServices()
	{
		$extraDef = new FactoryExtraDefinition();
		$extraDef->addService('service.a');
		$extraDef->addService('service.b');
		$extraDef->addService('service.c');
		$expectedServices = array('service.a' => 'service.a', 'service.b' => 'service.b', 'service.c' => 'service.c');
		$this->assertEquals($expectedServices, $extraDef->getServices(), '->getServices() and ->addServices() allows to add and retrieve the services manufactured by the factory');
	}
}