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
    protected function parseAdditionalDefinition($id, $service, $file, Definition $definition)
    {
    	if (isset($service['factory'])) 
    	{
    		$def = new FactoryDefinition();
    		$def->...($definition);

            if (!is_array($service['tags'])) {
                throw new InvalidArgumentException(sprintf('Parameter "tags" must be an array for service "%s" in %s.', $id, $file));
            }

            foreach ($service['tags'] as $tag) {
                if (!isset($tag['name'])) {
                    throw new InvalidArgumentException(sprintf('A "tags" entry is missing a "name" key for service "%s" in %s.', $id, $file));
                }

                $name = $tag['name'];
                unset($tag['name']);

                foreach ($tag as $attribute => $value) {
                    if (!is_scalar($value)) {
                        throw new InvalidArgumentException(sprintf('A "tags" attribute must be of a scalar-type for service "%s", tag "%s" in %s.', $id, $name, $file));
                    }
                }

                $definition->addTag($name, $tag);
            }
        }

        return $this->parent->parseAdditionalDefinition($id, $service, $file, $def);
    }
}