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

use Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition;

/**
 * @author Thomas Prelot
 */
class InterfaceExtraDefinitionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition::getName
	 * @covers Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition::setName
	 */
	public function testGetSetName()
	{
		$extraDef = new InterfaceExtraDefinition();
		$extraDef->setName('Interface');
		$this->assertEquals('Interface', $extraDef->getName(), '->getName() and ->setName() are the getter and setter for the name property');
	}
}