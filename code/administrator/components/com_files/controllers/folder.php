<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Folder Controller Class 
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesControllerFolder extends ComFilesControllerNode
{
    protected function _initialize(KConfig $config)
    {
    	$loggable = $this->getService('com://admin/activities.controller.behavior.loggable', array(
    		'title_column' => 'name'
    	)); 
    	$config->append(array(
    		'behaviors' => array($loggable)
    	));
    
    	parent::_initialize($config);
    }	
    
}