<?php
/**
 * @package     Koowa_Object
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Decoratable Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 */
interface ObjectDecoratable
{
    /**
     * Decorate the object
     *
     * When using decorate(), the object will be decorate by the decorator
     *
     * @@param   mixed  $decorator  An object that implements ObjectDecorator, ObjectIdentifier object
     *                              or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return   ObjectDecoratable
     */
    public function decorate($decorator, $config = array());
}