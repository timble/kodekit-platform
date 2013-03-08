<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Component Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ComExtensionsControllerComponent extends ComBaseControllerModel
{ 
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
        	'behaviors' => array('com://admin/activities.controller.behavior.loggable'),
        ));
    
        parent::_initialize($config);
    }
}