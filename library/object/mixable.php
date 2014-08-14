<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Mixable Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
interface ObjectMixable
{
    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed in objects, in a LIFO order.
     *
     * @param   mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectMixableInterface
     * @param   array $config  An optional associative array of configuration options
     * @return  ObjectMixinInterface
     * @throws  ObjectExceptionInvalidIdentifier If the identifier is not valid
     * @throws  \UnexpectedValueException If the mixin does not implement the ObjectMixinInterface
     */
    public function mixin($mixin, $config = array());

    /**
     * Checks if the object or one of it's mixin's inherits from a class.
     *
     * @param   string|object   $class The class to check
     * @return  bool Returns TRUE if the object inherits from the class
     */
    public function inherits($class);

    /**
     * Get a list of all the available methods
     *
     * This function returns an array of all the methods, both native and mixed in
     *
     * @return array An array
     */
    public function getMethods();
}