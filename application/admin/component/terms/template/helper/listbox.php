<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Terms
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author      Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Terms
 */

class TermsTemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function terms($config = array())
    {
    	$config = new Library\ObjectConfig($config);
    	$config->append(array(
    		'model' 	=> 'terms',
    		'value'		=> 'id',
    		'text'		=> 'title',
            'prompt'    => false
        ));
        
        $config->text = 'title';
		$config->sort = 'title';
    
    	return parent::_render($config);
    }
}