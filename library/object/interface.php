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
     * @param Config  $config  A Config object with optional configuration options
     */
    public function __construct(Config $config);

    /**
     * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
     *
     * @param	string|object	$identifier The class identifier or identifier object
     * @param	array  			$config     An optional associative array of configuration settings.
     * @return	Object Return object on success, throws exception on failure
     */
    public function getObject($identifier = null, array $config = array());

    /**
     * Get a service identifier.
     *
     * @param	string|object	$identifier The class identifier or identifier object
     * @return	ObjectIdentifier
     */
    public function getIdentifier($identifier = null);
}