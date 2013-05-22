<?php
/**
 * @package     Koowa_Object
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
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