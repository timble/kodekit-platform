<?php
/**
 * @version		$Id$
 * @package		Koowa
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class KoowaModelModules extends KModelAbstract
{	
	/**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
		parent::__construc($options);
		
		$this->_list = JModuleHelper::_load();
		$this->_total = count($this->_list);
	}
}