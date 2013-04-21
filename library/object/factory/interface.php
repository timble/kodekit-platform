<?php
/**
 * @package     Koowa_Object
 * @subpackage  Factory
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Factory Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Factory
 */
interface ObjectFactoryInterface
{
    /**
     * Instantiate an object
     *
     * @param  mixed         $identifier    The format name
     * @param  array|object  $config        A optional Config object or array of configuration options
     * @return ObjectInterface
     */
    public function getInstance($identifier, $config = array());
}