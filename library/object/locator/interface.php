<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Object  Locator Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Object\Locator\Interface
 */
interface ObjectLocatorInterface
{
    /**
     * Get the locator name
     *
     * @return string
     */
    public static function getName();

    /**
     * Returns a fully qualified class name for a given identifier.
     *
     * @param ObjectIdentifier $identifier An identifier object
     * @param bool  $fallback   Use the fallback sequence to locate the identifier
     * @return string|false  Return the class name on success, returns FALSE on failure
     */
    public function locate(ObjectIdentifier $identifier, $fallback = true);

    /**
     * Find an identifier class
     *
     * @param array  $info      The class information
     * @param bool   $fallback  If TRUE use the fallback sequence
     * @return bool|mixed
     */
    public function find(array $info, $fallback = true);

    /**
     * Get the list of class templates for an identifier
     *
     * @param string $identifier The package identifier
     * @return array The class templates for the identifier
     */
    public function getClassTemplates($identifier);

    /**
     * Register an identifier
     *
     * @param  string       $identifier
     * @param  string|array $namespace(s) Sequence of fallback namespaces
     * @return ObjectLocatorAbstract
     */
    public function registerIdentifier($identifier, $namespaces);

    /**
     * Get the namespace(s) for the identifier
     *
     * @param string $identifier The package identifier
     * @return string|false The namespace(s) or FALSE if the identifier does not exist.
     */
    public function getIdentifierNamespaces($identifier);
}