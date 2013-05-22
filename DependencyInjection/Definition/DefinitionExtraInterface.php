<?php

/*
 * This file is part of the Da Project.
 *
 * (c) Thomas Prelot <tprelot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da\DiBundle\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\Definition;

/**
 * DefinitionExtraInterface is the interface that a class should
 * implement to be used as an definition with extra parameters.
 *
 * @author Thomas Prelot
 */
interface DefinitionExtraInterface
{
	/**
     * Assimilate the definition to extend.
     *
     * @param Definition $definition The definition to extend.
     */
    function assimilateDefinition(Definition $definition);

	/**
	 * Get an extra definition.
	 *
	 * @param string $id The id of the extra definition.
	 *
	 * @return ExtraDefinitionInterface The extra definition.
	 */
	function getExtra($id);

	/**
	 * Set an extra definition.
	 *
	 * @param string                   $id              The id of the extra definition.
	 * @param ExtraDefinitionInterface $extraDefinition The extra definition.
	 */
	function setExtra($id, ExtraDefinitionInterface $extraDefinition);
}