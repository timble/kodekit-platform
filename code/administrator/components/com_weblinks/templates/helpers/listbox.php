<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2009 - 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Listbox Template Helper
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Components
 * @subpackage  Weblinks
 */
class ComWeblinksTemplateHelperListbox extends ComDefaultTemplateHelperListbox
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

	public function ordering( $config = array() )
	{
		$config = new KConfig($config);
		$config->append(array(
			'model'     => 'weblinks',
			'name'      => 'ordering',
			'value'     => 'ordering',
			'text'      => 'ordering',
			'deselect'  => false
		));

		return parent::_listbox($config);
	}
}