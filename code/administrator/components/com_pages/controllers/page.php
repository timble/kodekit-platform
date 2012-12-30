<?php
/**
 * @version     $Id: module.php 4997 2012-08-29 19:39:25Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Page Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesControllerPage extends ComDefaultControllerModel
{
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'behaviors' => array(
    		    'com://admin/pages.controller.behavior.closurable'
    	    )
    	));
    
    	parent::_initialize($config);
    }
}