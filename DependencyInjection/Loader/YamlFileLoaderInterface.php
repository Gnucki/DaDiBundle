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

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * YamlFileLoaderInterface is the interface that handle the decorator pattern
 * for the class YamlFileLoader.
 *
 * @author Thomas Prelot
 */
interface YamlFileLoaderInterface
{
	/**
     * Get the container.
     *
     * @return Container The container.
     */
    function getContainer();

    /**
     * Get the decorated instance.
     *
     * @return YamlFileLoaderInterface The decorated instance.
     */
    function getDecoratedInstance();

	/**
     * Loads a Yaml file.
     *
     * @param mixed  $file The resource.
     * @param string $type The resource type.
     */
    function load($file, $type = null);

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
    function parseExtraDefinition($id, $service, $file, Definition $definition);

    /**
     * Get a definition with extra parameters.
     *
     * @param Definition $definition The initial definition.
     *
     * @return Definition The definition with extra parameters.
     */
    function getDefinitionExtra(Definition $definition);

    /**
     * Parse a definition.
     *
     * @param string $id
     * @param array  $service
     * @param string $file
     *
     * @throws InvalidArgumentException When tags are invalid.
     */
    function parseDefinitionAccess($id, $service, $file);

    /**
     * Resolve services.
     *
     * @param string $value
     *
     * @return Reference
     */
    function resolveServicesAccess($value);
}