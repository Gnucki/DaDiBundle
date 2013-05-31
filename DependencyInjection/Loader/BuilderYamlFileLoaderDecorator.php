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
use Da\DiBundle\DependencyInjection\Definition\BuilderExtraDefinition;

/**
 * BuilderYamlFileLoaderDecorator is a decorator that add the builder parameter 
 * in the yaml service config file.
 *
 * @author Thomas Prelot
 */
class BuilderYamlFileLoaderDecorator extends AbstractYamlFileLoaderDecorator
{
    /**
	 * {@inheritdoc}
	 */
    public function parseExtraDefinition($id, $service, $file, Definition $definition)
    {
        $def = $definition;

    	if (isset($service['builder'])) 
    	{
            // Parse the extra definition.
            $builderExtra = new BuilderExtraDefinition();
            if (is_array($service['builder']))
            {
	            if (isset($service['builder']['class']))
	            	$builderExtra->setClass($service['builder']['class']);
	            if (isset($service['builder']['method']))
	            	$builderExtra->setMethod($service['builder']['method']);
	        	if (isset($service['builder']['service']))
	            	$builderExtra->setService($service['builder']['service']);
	        }
	        else
	        	$builderExtra->setService($service['builder']);

            // Add the extra definition to the definition.
            $def = $this->getDecoratedInstance()->getDefinitionExtra($definition);
            $def->setExtra('builder', $builderExtra);
        }

        return $this->getParent()->parseExtraDefinition($id, $service, $file, $def);
    }
}