<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Article Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ArticlesControllerArticle extends Library\ControllerModel
{ 
    protected function _initialize(Library\ObjectConfig $config)
    {
    	$config->append(array(
    		'behaviors' => array(
                'editable',
    	        'com:activities.controller.behavior.loggable',
    	        'com:revisions.controller.behavior.revisable',
    		    'com:languages.controller.behavior.translatable',
                'com:attachments.controller.behavior.attachable',
                'com:tags.controller.behavior.taggable'
    	    )
    	));
    
    	parent::_initialize($config);
    }
}