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
class ArticlesControllerArticle extends ApplicationControllerDefault
{ 
    protected function _initialize(Library\ObjectConfig $config)
    {
    	$config->append(array(
    		'behaviors' => array(
    	        'com:activities.controller.behavior.loggable',
    	        'com:versions.controller.behavior.revisable',
    		    'com:languages.controller.behavior.translatable',
                'com:attachments.controller.behavior.attachable',
                'com:terms.controller.behavior.taggable'
    	        //'cacheable'
    	    )
    	));
    
    	parent::_initialize($config);
    }
}