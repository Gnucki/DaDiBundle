<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\DependencyInjection\Loader;

use Symfony\Component\DependencyInjection\Definition;
use Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition;

/**
 * InterfaceYamlFileLoaderDecorator is a decorator that add the interface parameter 
 * in the yaml service config file.
 *
 * @author Thomas Prelot
 */
class InterfaceYamlFileLoaderDecorator extends AbstractYamlFileLoaderDecorator
{
    /**
	 * {@inheritdoc}
	 */
    public function parseExtraDefinition($id, $service, $file, Definition $definition)
    {
        $def = $definition;

    	if (isset($service['interface'])) 
    	{
            // Parse the extra definition.
            $interfaceExtra = new InterfaceExtraDefinition();
            $interfaceExtra->setName($service['interface']);

            // Add the extra definition to the definition.
            $def = $this->getDecoratedInstance()->getDefinitionExtra($definition);
            $def->setExtra('interface', $interfaceExtra);
        }

        return $this->getParent()->parseExtraDefinition($id, $service, $file, $def);
    }
}