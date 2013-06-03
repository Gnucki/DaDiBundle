DaDiBundle
==========

The DaDiBundle is a Symfony2's bundle that allows to use advanced dependency injection features.
You can use some new parameters (like `interface` for instance) in the configuration of your services and implement your own.

Installation
------------

Add the following line to the require section of you composer.json file:

``` json
"da/di-bundle": "dev-master"
```

Run the composer update command:

``` bash
composer update --dev
```

Add the following line to your AppKernel.php file:

``` php
new Da\DiBundle\DaDiBundle(),
```

You should now be able to use the DaDiBundle.
To benefit of the features of this bundle, you have to use a new `FileLoader` in your dependency injection:

``` php
// Me/MyBundle/DependencyInjection/MeMyBundleExtension.php

namespace Me\MyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Da\DiBundle\DependencyInjection\Loader\YamlFileLoader;

class MeMyBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = YamlFileLoader::decorate($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // ...
    }
}
```

Using new parameters
--------------------

This bundle adds some new parameters to help you and demonstrates how you could enhance your experience with the services and the depency injection.

### Interface

The paramater `interface` allows you to check that a service implements an interface:

``` yaml
parameters:
    router.class: Me\MyRoutingBundle\Router

services:
    me-routing.router:
    	interface: Me\MyRoutingBundle\RouterInterface
        class: %router.class%
```

### Factory

The paramater `factory` allows you to organize your services which have the same responsability:

``` yaml
services:
    me-file.parser:
    	interface: Me\FileBundle\Parser\ParserInterface
        factory:
        	yaml:
        		class: Me\FileBundle\Parser\YamlParser
        	xml:
        		class: Me\FileBundle\Parser\XmlParser
        	php:
        		interface: Me\FileBundle\Parser\CodeParserInterface
        		class: Me\FileBundle\Parser\PhpParser
```

You can then access the parser for yaml file service with the id `me-file.parser.yaml` for instance.
The service `me-file.parser` is a factory you can use like that in a controller:

``` php
	$yamlParser = $this->container->get('me-file.parser')->get('yaml');
```

>**Note:**
>All the parameters of the factory are applied to its manufactored services.
>In this example, the services `me-file.parser.yaml` and `me-file.parser.xml` have to implement the interface `Me\FileBundle\Parser\ParserInterface` define at the `me-file.parser` level.
>The service `me-file.parser.php` overrides the `interface` parameter and has to implement the interface `Me\FileBundle\Parser\CodeParserInterface` instead.

### Builder

The paramater `builder` is just a syntaxic sugar that helps to differentiate the `factory` parameter from the native `factory_class`, `factory_method`, `factory_service`:

``` yaml
services:
    me-file.lexer:
    	builder:
    		method: build
    		service: me-file.lexer.builder
    me-file.lexer.builder:
    	class: Me\FileBundle\Lexer\Builder
```

is equivalent to:

``` yaml
services:
    me-file.lexer:
    	builder: me-file.lexer.builder # build is the default name of the method.
    me-file.lexer.builder:
    	class: Me\FileBundle\Lexer\Builder
```

is equivalent to:

``` yaml
services:
    me-file.lexer:
    	factory_method: build
    	factory_service: me-file.lexer.builder
    me-file.lexer.builder:
    	class: Me\FileBundle\Lexer\Builder
```

Implementing your own parameter
-------------------------------

This bundle allows you to define your own parameters in a structured way.
Let's see the example of the parameter `interface`.

### Create an extra definition

``` php
// Da/DiBundle/DependencyInjection/Definition/InterfaceExtraDefinition.php

namespace Da\DiBundle\DependencyInjection\Definition;

class InterfaceExtraDefinition implements ExtraDefinitionInterface
{
	private $name;

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}
}
```

This extra definition contains the informations needed for your parameter.

### Add a decorator to the yaml file loader

``` php
// Da/DiBundle/DependencyInjection/Loader/InterfaceYamlFileLoaderDecorator.php

namespace Da\DiBundle\DependencyInjection\Loader;

use Symfony\Component\DependencyInjection\Definition;
use Da\DiBundle\DependencyInjection\Definition\InterfaceExtraDefinition;

class InterfaceYamlFileLoaderDecorator extends AbstractYamlFileLoaderDecorator
{
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
```

This decorator (see the design pattern) takes the parameter `interface` into account and add it as an extra definition in the definition of the service.
You have to declare it in your bundle:

``` php
// Da/DiBundle/DependencyInjection/Compiler/CheckInterfaceValidityPass.php

namespace Da\DiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Da\DiBundle\DependencyInjection\Loader\YamlFileLoader;

class DaDiBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        parent::build($container);

		// ...

        YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\InterfaceYamlFileLoaderDecorator');
    }
}
```

### Add a compiler pass

``` php
// Da/DiBundle/DependencyInjection/Compiler/CheckInterfaceValidityPass.php

namespace Da\DiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtraInterface;

class CheckInterfaceValidityPass implements CompilerPassInterface
{
    private $container;

    public function process(ContainerBuilder $container)
    {
        $this->container = $container;

        foreach (array_keys($container->getDefinitions()) as $id) 
        {
            // yes, we are specifically fetching the definition from the
            // container to ensure we are not operating on stale data
            $definition = $container->getDefinition($id);
            if (!$definition instanceof DefinitionExtraInterface || !$definition->getExtra('interface'))
                continue;

            $this->checkDefinition($id, $definition);
        }
    }

    private function checkDefinition($id, DefinitionExtraInterface $definition)
    {
        $interfaceName = $definition->getExtra('interface')->getName();
        $className = $definition->getClass();

        $class = new \ReflectionClass($className);
        if (!interface_exists($interfaceName))
            throw new InvalidArgumentException('Interface "'.$interfaceName.'" not found.');
        if (!$class->implementsInterface($interfaceName))
        	throw new RuntimeException('The class "'.$className.'" of the service "'.$id.'" should implement the interface "'.$interfaceName.'".');
    }
}
```

This compiler pass checks that your service implements the defined interface.
You have to declare it in your bundle:

``` php
// Da/DiBundle/DaDiBundle.php

namespace Da\DiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Da\DiBundle\DependencyInjection\Loader\YamlFileLoader;
use Da\DiBundle\DependencyInjection\Compiler\CheckInterfaceValidityPass;

class DaDiBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        parent::build($container);

		// ...

		YamlFileLoader::addDecorator('Da\DiBundle\DependencyInjection\Loader\InterfaceYamlFileLoaderDecorator');
        $container->addCompilerPass(new CheckInterfaceValidityPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
```

Limitations
-----------

All the features have been, for the time being, only developped for yaml configuration file.

Tests
-----

As the developpement of new parameters can affect others, you can run phpunit on this bundle to check that all the features are still ok after developping your own.