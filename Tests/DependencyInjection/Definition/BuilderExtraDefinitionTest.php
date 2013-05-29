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

use Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition;

/**
 * @author Thomas Prelot
 */
class BuilderExtraDefinitionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition::getClass
	 * @covers Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition::setClass
	 */
	public function testGetSetClass()
	{
		$extraDef = new BuilderExtraDefinition();
		$extraDef->setClass('Class');
		$this->assertEquals('Class', $extraDef->getClass(), '->getClass() and ->setClass() are the getter and setter for the class property');
	}

	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition::getMethod
	 * @covers Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition::setMethod
	 */
	public function testGetSetMethod()
	{
		$extraDef = new BuilderExtraDefinition();
		$extraDef->setMethod('get');
		$this->assertEquals('get', $extraDef->getMethod(), '->getMethod() and ->setMethod() are the getter and setter for the method property');
		
		$extraDef = new BuilderExtraDefinition();
		$this->assertEquals('build', $extraDef->getMethod(), '->getMethod() returns "build" by default');
	}

	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition::getService
	 * @covers Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition::setService
	 */
	public function testGetSetService()
	{
		$extraDef = new BuilderExtraDefinition();
		$extraDef->setService('service');
		$this->assertEquals('service', $extraDef->getService(), '->getService() and ->setService() are the getter and setter for the service property');
	}
}