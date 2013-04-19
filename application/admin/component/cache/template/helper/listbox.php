<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Listbox Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class CacheTemplateHelperListbox extends Library\TemplateHelperListbox
{
	public function groups( $config = array())
	{	
	    $config = new Library\ObjectConfig($config);
		$config->append(array(
			'model'		=> 'groups',
			'name' 		=> 'group',
			'value'		=> 'name',
			'text'		=> 'name'
		));
	
		return parent::_listbox($config);
	}
}