<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions    
 */

class ComExtensionsTemplateHelperListbox extends KTemplateHelperListbox
{
	/**
	 * Customized to add a few attributes.
	 * 
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 * @see __call()
	 */
	protected function _listbox($config = array())
 	{
		$config = new KConfig($config);
		
		$config->append(array(
			'filter' => array(
				'application' => $config->application
			)
		));

		return parent::_listbox($config);
 	}
 	
 	public function positions($config = array())
 	{
 	    $config = new KConfig($config);
		$config->append(array(
			'model' 	=> 'modules',
			'name'		=> 'position',
		));

		return $this->_listbox($config);
 	}
 	
    public function types($config = array())
 	{
 	    $config = new KConfig($config);
		$config->append(array(
			'model' 	=> 'modules',
			'name'		=> 'type',
		));

		return $this->_listbox($config);
 	}
}