<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Framework;

/**
 * Page Controller
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class ControllerPage extends \BaseControllerModel
{
    protected function _initialize(Framework\Config $config)
    {
    	$config->append(array(
    		'behaviors' => array(
    		    'com:pages.controller.behavior.closurable'
    	    )
    	));
    
    	parent::_initialize($config);
    }
}