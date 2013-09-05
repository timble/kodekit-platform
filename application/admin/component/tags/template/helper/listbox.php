<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Component\Tags
 */
class TagsTemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function tags($config = array())
    {
    	$config = new Library\ObjectConfig($config);
    	$config->append(array(
    		'model'  => 'tags',
    		'value'	 => 'id',
    		'label'	 => 'title',
            'prompt' => false
        ));
        
        $config->label = 'title';
		$config->sort  = 'title';
    
    	return parent::_render($config);
    }
}