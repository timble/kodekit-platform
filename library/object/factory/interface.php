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