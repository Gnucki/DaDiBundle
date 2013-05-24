<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtraInterface;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtra;
use Da\DiBundle\DependencyInjection\Definition\DefinitionDecoratorExtra;

/**
 * This compiler pass handle the factory parameter of the definition.
 *
 * @author Thomas Prelot
 */
class ResolveBuilderDefinitionPass implements CompilerPassInterface
{
    /**
     * The container.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Process the ContainerBuilder.
     *
     * @param ContainerBuilder $container The container builder.
     */
    public function process(ContainerBuilder $container)
    {
        $this->container = $container;

        foreach (array_keys($container->getDefinitions()) as $id) 
        {
            // yes, we are specifically fetching the definition from the
            // container to ensure we are not operating on stale data
            $definition = $container->getDefinition($id);
            if (!$definition instanceof DefinitionExtraInterface || !$definition->getExtra('builder'))
                continue;

            $this->resolveDefinition($id, $definition);
        }
    }

    /**
     * Resolve the definition.
     *
     * @param string              $id         The identifier of the definition.
     * @param DefinitionDecorator $definition The definition.
     *
     * @return Definition
     */
    private function resolveDefinition($id, DefinitionExtraInterface $definition)
    {
        $builder = $definition->getExtra('builder');

        $definition->setFactoryClass($builder->getClass());
        $definition->setFactoryMethod($builder->getMethod());
        $definition->setFactoryService($builder->getService());
    }
}
