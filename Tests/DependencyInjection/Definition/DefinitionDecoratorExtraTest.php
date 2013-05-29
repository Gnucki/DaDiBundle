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

use Symfony\Component\DependencyInjection\Definition;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtra;
use Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra;
use Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition;
use Da\DiBundle\DependencyInjection\Definition\FactoryExtraDefinition;
use Da\DiBundle\Tests\Fixtures\Definition\BadDefinition;

/**
 * @author Thomas Prelot
 */
class DefinitionDecoratorExtraTest extends \PHPUnit_Framework_TestCase
{
    private function getDefinitionExtra()
    {
        return new DefinitionDecoratorExtra('service.parent');
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra::__construct
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra::getExtra
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra::setExtra
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra::unsetExtra
     */
    public function testGetSetExtra()
    {
        $extraDef = new InterfaceExtraDefinition();
        $def = $this->getDefinitionExtra();
        $def->setExtra('interface', $extraDef);
        $this->assertInstanceOf('Da\DiBundle\DependencyInjection\Definition\ExtraDefinitionInterface', $def->getExtra('interface'), '->getExtra() and ->setExtra() allow to get and set extra definitions');
        $this->assertNull($def->getExtra('factory'), '->getExtra() returns "null" if the extra definition does not exist'); 

        $def->unsetExtra('interface');
        $this->assertNull($def->getExtra('interface'), '->unsetExtra() removes an extra definition');
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra::getExtras
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra::setExtras
     */
    public function testGetSetExtras()
    {
        $extras = array();
        $extraDef = new InterfaceExtraDefinition();
        $extras['interface'] = $extraDef;
        $extraDef = new FactoryExtraDefinition();
        $extras['factory'] = $extraDef;

        $def = $this->getDefinitionExtra();
        $def->setExtras($extras);
        $this->assertEquals($extras, $def->getExtras(), '->getExtras() and ->setExtras() allow to get and set extra definitions');
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra::setExtras
     * @expectedException Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function testSetBadExtras()
    {
        $extras = array();
        $extraDef = new InterfaceExtraDefinition();
        $extras['interface'] = $extraDef;
        $extraDef = new BadDefinition();
        $extras['factory'] = $extraDef;

        $def = $this->getDefinitionExtra();
        $def->setExtras($extras);
    }

    /**
     * @covers Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra::assimilateDefinition
     */
    public function testAssimilateDefinition()
    {
        $definition = new Definition();
        $definition->setClass('Class');
        $def = $this->getDefinitionExtra();
        $def->assimilateDefinition($definition);
        $this->assertEquals('Class', $def->getClass(), '->assimilateDefinition() copies the parameters of the definition in input to the abstracted definition');
    }
}