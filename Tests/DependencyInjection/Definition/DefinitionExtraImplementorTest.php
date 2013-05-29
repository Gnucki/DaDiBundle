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

use Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor;
use Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition;
use Da\DiBundle\DependencyInjection\Definition\FactoryExtraDefinition;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtra;
use Da\DiBundle\Tests\Fixtures\Definition\BadDefinition;

/**
 * @author Thomas Prelot
 */
class DefinitionExtraImplementorTest extends \PHPUnit_Framework_TestCase
{
	private function getDefinitionExtraImplementor()
	{
		$definition = new DefinitionExtra();
		return new DefinitionExtraImplementor($definition);
	}

	/**
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::__construct
	 * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::getAbstraction
	 */
	public function testGetAbstraction()
	{
		$def = $this->getDefinitionExtraImplementor();
		$this->assertInstanceOf('Da\DiBundle\DependencyInjection\Definition\DefinitionExtraInterface', $def->getAbstraction(), '->getAbstraction() returns the abstracted definition of the implementor (see bridge pattern)');
	}

	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::getExtra
	 * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::setExtra
	 * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::unsetExtra
	 */
	public function testGetSetExtra()
	{
		$extraDef = new InterfaceExtraDefinition();
		$def = $this->getDefinitionExtraImplementor();
		$def->setExtra('interface', $extraDef);
		$this->assertInstanceOf('Da\DiBundle\DependencyInjection\Definition\ExtraDefinitionInterface', $def->getExtra('interface'), '->getExtra() and ->setExtra() allow to get and set extra definitions');
		$this->assertNull($def->getExtra('factory'), '->getExtra() returns "null" if the extra definition does not exist');	

		$def->unsetExtra('interface');
		$this->assertNull($def->getExtra('interface'), '->unsetExtra() removes an extra definition');
	}

	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::getExtras
	 * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::setExtras
	 */
	public function testGetSetExtras()
	{
		$extras = array();
		$extraDef = new InterfaceExtraDefinition();
		$extras['interface'] = $extraDef;
		$extraDef = new FactoryExtraDefinition();
		$extras['factory'] = $extraDef;

		$def = $this->getDefinitionExtraImplementor();
		$def->setExtras($extras);
		$this->assertEquals($extras, $def->getExtras(), '->getExtras() and ->setExtras() allow to get and set extra definitions');
	}

	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::setExtras
	 * @expectedException Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
	 */
	public function testSetBadExtras()
	{
		$extras = array();
		$extraDef = new InterfaceExtraDefinition();
		$extras['interface'] = $extraDef;
		$extraDef = new BadDefinition();
		$extras['factory'] = $extraDef;

		$def = $this->getDefinitionExtraImplementor();
		$def->setExtras($extras);
	}

	/**
	 * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionExtraImplementor::assimilateDefinition
	 */
	public function testAssimilateDefinition()
	{
		$definition = new DefinitionExtra();
        $definition->setClass('Class');
        $def = $this->getDefinitionExtraImplementor();
        $def->assimilateDefinition($definition);
        $this->assertEquals('Class', $def->getAbstraction()->getClass(), '->assimilateDefinition() copies the parameters of the definition in input to the abstracted definition');
	}
}