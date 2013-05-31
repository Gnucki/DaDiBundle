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
use Da\DiBundle\DependencyInjection\Definition\FactoryExtraDefinition;

/**
 * FactoryYamlFileLoaderDecorator is a decorator that add the factory parameter 
 * in the yaml service config file.
 *
 * @author Thomas Prelot
 */
class FactoryYamlFileLoaderDecorator extends AbstractYamlFileLoaderDecorator
{
    /**
	 * {@inheritdoc}
	 */
    public function parseExtraDefinition($id, $service, $file, Definition $definition)
    {
        $def = $definition;

    	if (isset($service['factory'])) 
    	{
            // Parse the extra definition.
            $factoryExtra = new FactoryExtraDefinition();
            if (!is_array($service['factory']))
                throw new InvalidArgumentException(sprintf('Parameter "factory" must be an array for service "%s" in %s.', $id, $file));

            foreach ($service['factory'] as $manufactoredServiceId => $manufactoredService) 
            {
                $manufactoredServiceId = $id.'.'.$manufactoredServiceId;
                $this->getDecoratedInstance()->parseDefinitionAccess($manufactoredServiceId, $manufactoredService, $file);
                $manufactoredServiceDef = $this->getContainer()->getDefinition($manufactoredServiceId);
                $manufactoredServiceDef = $this->getDecoratedInstance()->parseExtraDefinition($manufactoredServiceId, $manufactoredService, $file, $manufactoredServiceDef);
                $factoryExtra->addService($manufactoredServiceId);
                $manufactoredServiceDef = $this->getDecoratedInstance()->getDefinitionExtra($manufactoredServiceDef);
                $this->getContainer()->setDefinition($manufactoredServiceId, $manufactoredServiceDef);
            }

            // Add the extra definition to the definition.
            $def = $this->getDecoratedInstance()->getDefinitionExtra($definition);
            $def->setExtra('factory', $factoryExtra);
            $def->setAbstract(true);
        }

        return $this->getParent()->parseExtraDefinition($id, $service, $file, $def);
    }
}