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

/**
 * This compiler pass handle the factory parameter of the definition.
 *
 * @author Thomas Prelot
 */
class ResolveFactoryDefinitionPass implements CompilerPassInterface
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
            if (!$definition instanceof DefinitionExtraInterface || !$definition->getExtra('factory'))
                continue;

            $this->resolveDefinition($id, $definition);
        }
    }

    /**
     * Resolves the definition
     *
     * @param string              $id         The identifier of the definition.
     * @param DefinitionDecorator $definition The definition.
     *
     * @return Definition
     */
    private function resolveDefinition($id, DefinitionExtraInterface $definition)
    {
        $factory = $definition->getExtra('factory');

        foreach ($factory->getServices() as $manufactoredServiceId) 
        {
            // Override the global parameters of the factory with individual ones.
            $def = new Definition();
            $def->setArguments($definition->getArguments());
            $def->setMethodCalls($definition->getMethodCalls());
            $def->setProperties($definition->getProperties());
            $def->setFactoryClass($definition->getFactoryClass());
            $def->setFactoryMethod($definition->getFactoryMethod());
            $def->setFactoryService($definition->getFactoryService());
            $def->setConfigurator($definition->getConfigurator());
            $def->setFile($definition->getFile());
            $def->setPublic($definition->isPublic());
            $def->setAbstract(false);
            $def->setScope($definition->getScope());
            $def->setTags($definition->getTags());

            $manufactoredServiceDef = $this->container->getDefinition($manufactoredServiceId);
            if ($manufactoredServiceDef->getClass())
                $def->setClass($manufactoredServiceDef->getClass());
            if ($manufactoredServiceDef->getFactoryClass())
                $def->setFactoryClass($manufactoredServiceDef->getFactoryClass());
            if ($manufactoredServiceDef->getFactoryMethod())
                $def->setFactoryMethod($manufactoredServiceDef->getFactoryMethod());
            if ($manufactoredServiceDef->getFactoryService())
                $def->setFactoryService($manufactoredServiceDef->getFactoryService());
            if ($manufactoredServiceDef->getConfigurator())
                $def->setConfigurator($manufactoredServiceDef->getConfigurator());
            if ($manufactoredServiceDef->getFile())
                $def->setFile($manufactoredServiceDef->getFile());
            if ($manufactoredServiceDef->isPublic() !== null)
                $def->setPublic($manufactoredServiceDef->isPublic());
            if ($manufactoredServiceDef->getArguments())
                $def->setArguments($manufactoredServiceDef->getArguments());
            if ($manufactoredServiceDef->getProperties())
                $def->setProperties($manufactoredServiceDef->getProperties());
            if ($manufactoredServiceDef->getMethodCalls())
                $def->setMethodCalls($manufactoredServiceDef->getMethodCalls());
            if ($manufactoredServiceDef->isAbstract() !== null)
                $def->setAbstract($manufactoredServiceDef->isAbstract());
            if ($manufactoredServiceDef->getScope())
                $def->setScope($manufactoredServiceDef->getScope());
            if ($manufactoredServiceDef->getTags())
                $def->setTags($manufactoredServiceDef->getTags());
            
            $this->container->setDefinition($manufactoredServiceId, $def);
        }

        $def = new Definition();
        $def->setClass('Da\DiBundle\DependencyInjection\Service\ServiceFactory');
        $def->setArguments(array($id, new Reference('service_container')));
        $def->setPublic(true);
        $this->container->setDefinition($id, $def);
    }
}
