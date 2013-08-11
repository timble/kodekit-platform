<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Decoratable Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
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