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
     * @param YamlFileLoaderDecorator $decorator The decorator.
     */
	public static function addDecorator(YamlFileLoaderDecorator $decorator)
	{
		self::$decorators[] = $decorator;
	}

	/**
	 * Get the decorated instance of the class.
	 *
	 * @return YamlFileLoaderDecorator The decorated instance of the class.
	 */
	public static function decorate()
	{
		if (!self::$decoratedInstance)
		{
			self::$decoratedInstance = $this;
			foreach (self::$decorators as $decorator)
			{
				self::$decoratedInstance = $decorator->setParent(self::$decoratedInstance);
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

        $this->parseAdditionalDefinitions($this->content, $file);
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
    	$this->content = $this->validate(Yaml::parse($file), $file);
        return $this->content;
    }

    /**
     * Parses additional definitions.
     *
     * @param array  $content
     * @param string $file
     */
    private function parseAdditionalDefinitions($content, $file)
    {
        if (!isset($content['services']))
            return;

        foreach ($content['services'] as $id => $service) 
        {
        	$definition = $this->container->getDefinition($id);
            $definition = $this->parseAdditionalDefinition($id, $service, $file, $definition);
            $this->container->setDefinition($id, $definition);
        }
    }

    /**
     * Parses an additional definition.
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
    protected function parseAdditionalDefinition($id, $service, $file, Definition $definition)
    {
        return $definition;
    }
}