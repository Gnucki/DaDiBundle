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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class CompilerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('da.di.compiler'))
            return;

        $definition = $container->getDefinition('da.di.compiler');

        // Subscription of the providers of compilation configuration to the compiler.
        $taggedServices = $container->findTaggedServiceIds('da.di.compilation.provider');
        foreach ($taggedServices as $id => $tagAttributes) 
        {
            foreach ($tagAttributes as $attributes) 
            {
                $definition->addMethodCall
                (
                    'addCompilationProvider',
                    array(new Reference($id))
                );
            }
        }
    }
}