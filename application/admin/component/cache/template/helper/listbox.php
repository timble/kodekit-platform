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
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Cache
 */
class CacheTemplateHelperListbox extends Library\TemplateHelperListbox
{
	public function groups( $config = array())
	{	
	    $config = new Library\ObjectConfig($config);
		$config->append(array(
			'model'	=> 'groups',
			'name' 	=> 'group',
			'value'	=> 'name',
			'label'	=> 'name'
		));
	
		return parent::_listbox($config);
	}
}