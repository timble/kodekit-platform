<?php
/**
 * @package     Nooku_Server
 * @subpackage  Terms
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Tags;

/**
 * Tag Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Tags
 */
abstract class TagsControllerTag extends Tags\ControllerTag
{ 
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	'behaviors' => array('com:activities.controller.behavior.loggable'),
        ));

        //Force the toolbars
        $config->toolbars = array('menubar', 'com:tags.controller.toolbar.tag');
        
        parent::_initialize($config);
    }
}