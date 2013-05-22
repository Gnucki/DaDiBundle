<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Da\DiBundle\DependencyInjection\Loader\YamlFileLoader;
use Da\DiBundle\DependencyInjection\Compiler\ResolveFactoryDefinitionPass;

class DaDiBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        parent::build($container);

		YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\FactoryYamlFileLoaderDecorator');
        $container->addCompilerPass(new ResolveFactoryDefinitionPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
    }
}
