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

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as BaseYamlFileLoader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocatorInterface;
use Da\DiBundle\DependencyInjection\Definition\DefinitionExtra;

/**
 * YamlFileLoader loads YAML files service definitions.
 *
 * This is an overriding of the YamlFileLoader of Symfony.
 *
 * @author Thomas Prelot
 */
class YamlFileLoader extends BaseYamlFileLoader
{
	/**
	 * The content of the loaded file.
	 *
	 * @var mixed
	 */
	protected $content;

	/**
	 * The list of the decorators.
	 *
	 * @var array
	 */
	private static $decorators = array();

	/**
	 * The decorated instance of the class.
	 *
	 * @var YamlFileLoaderDecorator
	 */
	private static $decoratedInstance;

	/**
     * Add a decorator to this class.
     *
     * @param string  $decoratorClassName A decorator class name.
     * @param integer $order     The order.
     */
	public static function addDecorator($decoratorClassName, $order = 0)
	{
        if (!is_int($order))
            throw new \InvalidArgumentException('The order must be an integer.');

        $isDecoratorAdded = false;
        foreach (self::$decorators as $i => $decorator)
        {
            if ($order < $decorator['order'])
            {
                self::$decorators = array_merge(array_slice(self::$decorators, 0, $i), array(array('order' => $order, 'name' => $decoratorClassName)), array_values(array_slice(self::$decorators, $i)));
                $isDecoratorAdded = true;
            }
            
        }
        if (!$isDecoratorAdded)
           self::$decorators[] = array('order' => $order, 'name' => $decoratorClassName);
	}

	/**
	 * Get the decorated instance of the class.
	 *
     * @param ContainerBuilder     $container A ContainerBuilder instance.
     * @param FileLocatorInterface $locator   A FileLocator instance.
     *
	 * @return YamlFileLoaderDecorator The decorated instance of the class.
     *
     * @throws InvalidArgumentException if the container is not specified for the initialisation of the decorators.
     * @throws InvalidArgumentException if the locator is not specified for the initialisation of the decorators.
	 */
	public static function decorate(ContainerBuilder $container = null, FileLocatorInterface $locator = null)
	{
		if (!self::$decoratedInstance)
		{
            if (!$container)
                throw new \InvalidArgumentException('The container must be specified for the initialisation of the decorators.');
            else if (!$locator)
                throw new \InvalidArgumentException('The locator must be specified for the initialisation of the decorators.');

			self::$decoratedInstance = new YamlFileLoader($container, $locator);
            $decorators = self::$decorators;
			while (($decorator = array_pop($decorators)))
			{
                self::$decoratedInstance = new $decorator['name']($container, $locator, self::$decoratedInstance);
			}
		}
		return self::$decoratedInstance;
	}

	/**
     * Loads a Yaml file.
     *
     * @param mixed  $file The resource
     * @param string $type The resource type
     */
    public function load($file, $type = null)
    {
    	parent::load($file, $type);

        $this->parseExtraDefinitions($this->content, $file);
    }

    /**
     * Loads a YAML file.
     *
     * @param string $file
     *
     * @return array The file content
     */
    protected function loadFile($file)
    {
    	$this->content = parent::loadFile($file);
        return $this->content;
    }

    /**
     * Parses extra definitions.
     *
     * @param array  $content
     * @param string $file
     */
    private function parseExtraDefinitions($content, $file)
    {
        if (!isset($content['services']))
            return;

        foreach ($content['services'] as $id => $service) 
        {
        	$definition = $this->container->getDefinition($id);
            $definition = $this->parseExtraDefinition($id, $service, $file, $definition);
            $this->container->setDefinition($id, $definition);
        }
    }

    /**
     * Parses an extra definition.
     *
     * @param string     $id
     * @param array      $service
     * @param string     $file
     * @param Definition $definition
     *
     * @return Definition The new definition.
     *
     * @throws InvalidArgumentException When the format of a parameter is bad.
     */
    protected function parseExtraDefinition($id, $service, $file, Definition $definition)
    {
        return $definition;
    }

    /**
     * Get a definition with extra parameters.
     *
     * @param Definition $definition The initial definition.
     *
     * @return Definition The definition with extra parameters.
     */
    protected function getDefinitionExtra(Definition $definition)
    {
        if ($definition instanceof DefinitionExtraInterface)
            return $definition;
        else if ($definition instanceof DefinitionDecorator)
            $def = new DefinitionDecoratorExtra($definition); // PARENT???
        else
            $def = new DefinitionExtra($definition);
        return $def;
    }

    /**
     * DUPLICATION OF THE MOTHER PRIVATE CLASS' METHOD (need a protected!).
     *
     * @param string $id
     * @param array  $service
     * @param string $file
     *
     * @throws InvalidArgumentException When tags are invalid
     */
    protected function DUPLICATED_parseDefinition($id, $service, $file)
    {
        if (is_string($service) && 0 === strpos($service, '@')) {
            $this->container->setAlias($id, substr($service, 1));

            return;
        } elseif (isset($service['alias'])) {
            $public = !array_key_exists('public', $service) || (Boolean) $service['public'];
            $this->container->setAlias($id, new Alias($service['alias'], $public));

            return;
        }

        if (isset($service['parent'])) {
            $definition = new DefinitionDecorator($service['parent']);
        } else {
            $definition = new Definition();
        }

        if (isset($service['class'])) {
            $definition->setClass($service['class']);
        }

        if (isset($service['scope'])) {
            $definition->setScope($service['scope']);
        }

        if (isset($service['synthetic'])) {
            $definition->setSynthetic($service['synthetic']);
        }

        if (isset($service['public'])) {
            $definition->setPublic($service['public']);
        }

        if (isset($service['abstract'])) {
            $definition->setAbstract($service['abstract']);
        }

        if (isset($service['factory_class'])) {
            $definition->setFactoryClass($service['factory_class']);
        }

        if (isset($service['factory_method'])) {
            $definition->setFactoryMethod($service['factory_method']);
        }

        if (isset($service['factory_service'])) {
            $definition->setFactoryService($service['factory_service']);
        }

        if (isset($service['file'])) {
            $definition->setFile($service['file']);
        }

        if (isset($service['arguments'])) {
            $definition->setArguments($this->DUPLICATED_resolveServices($service['arguments']));
        }

        if (isset($service['properties'])) {
            $definition->setProperties($this->DUPLICATED_resolveServices($service['properties']));
        }

        if (isset($service['configurator'])) {
            if (is_string($service['configurator'])) {
                $definition->setConfigurator($service['configurator']);
            } else {
                $definition->setConfigurator(array($this->DUPLICATED_resolveServices($service['configurator'][0]), $service['configurator'][1]));
            }
        }

        if (isset($service['calls'])) {
            foreach ($service['calls'] as $call) {
                $args = isset($call[1]) ? $this->DUPLICATED_resolveServices($call[1]) : array();
                $definition->addMethodCall($call[0], $args);
            }
        }

        if (isset($service['tags'])) {
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

        $this->container->setDefinition($id, $definition);
    }

    /**
     * DUPLICATION OF THE MOTHER PRIVATE CLASS' METHOD (need a protected!).
     *
     * @param string $value
     *
     * @return Reference
     */
    protected function DUPLICATED_resolveServices($value)
    {
        if (is_array($value)) {
            $value = array_map(array($this, 'DUPLICATED_resolveServices'), $value);
        } elseif (is_string($value) &&  0 === strpos($value, '@')) {
            if (0 === strpos($value, '@@')) {
                $value = substr($value, 1);
                $invalidBehavior = null;
            } elseif (0 === strpos($value, '@?')) {
                $value = substr($value, 2);
                $invalidBehavior = ContainerInterface::IGNORE_ON_INVALID_REFERENCE;
            } else {
                $value = substr($value, 1);
                $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
            }

            if ('=' === substr($value, -1)) {
                $value = substr($value, 0, -1);
                $strict = false;
            } else {
                $strict = true;
            }

            if (null !== $invalidBehavior) {
                $value = new Reference($value, $invalidBehavior, $strict);
            }
        }

        return $value;
    }
}