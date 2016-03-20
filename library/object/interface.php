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
 * Object Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Object
 */
interface ObjectInterface
{
    /**
     * Constructor
     *
     * Allow configuration of the object via the constructor.
     *
     * @param ObjectConfig  $config  A ObjectConfig object with optional configuration options
     */
    public function __construct(ObjectConfig $config);

    /**
     * Get an instance of an object identifier
     *
     * @param ObjectIdentifier|string $identifier An ObjectIdentifier or valid identifier string
     * @param array  			      $config     An optional associative array of configuration settings.
     * @return ObjectInterface  Return object on success, throws exception on failure.
     */
    public function getObject($identifier, array $config = array());

    /**
     * Gets the object identifier.
     *
     * If no identifier is passed the object identifier of this object will be returned. Function recursively
     * resolves identifier aliases and returns the aliased identifier.
     *
     * @param   string|object    $identifier A valid identifier string or object implementing ObjectInterface
     * @return  ObjectIdentifier
     */
    public function getIdentifier($identifier = null);

    /**
     * Get the object configuration
     *
     * If no identifier is passed the object config of this object will be returned. Function recursively
     * resolves identifier aliases and returns the aliased identifier.
     *
     *  @param   string|object    $identifier A valid identifier string or object implementing ObjectInterface
     * @return ObjectConfig
     */
    public function getConfig($identifier = null);
}