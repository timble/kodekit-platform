<?php
/**
 * @package     Nooku_Server
 * @subpackage  Tags
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author      Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package     Nooku_Server
 * @subpackage  Tags
 */

class TagsTemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function tags($config = array())
    {
    	$config = new Library\ObjectConfig($config);
    	$config->append(array(
    		'model' 	=> 'tags',
    		'value'		=> 'id',
    		'text'		=> 'title',
            'prompt'    => false
        ));
        
        $config->text = 'title';
		$config->sort = 'title';
    
    	return parent::_render($config);
    }
}