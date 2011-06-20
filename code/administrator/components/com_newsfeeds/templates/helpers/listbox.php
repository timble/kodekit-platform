<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper Class
 *
 * @author      Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */

class ComNewsfeedsTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function category( $config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'model'		=> 'categories',
			'name' 		=> 'category',
			'value'		=> 'id',
			'text'		=> 'title',
		));

		return parent::_listbox($config);
	}
}
