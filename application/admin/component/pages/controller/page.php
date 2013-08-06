<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Library;

/**
 * Page Controller
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class PagesControllerPage extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
    	$config->append(array(
    		'behaviors' => array('editable', 'closurable')
    	));
    
    	parent::_initialize($config);
    }
}