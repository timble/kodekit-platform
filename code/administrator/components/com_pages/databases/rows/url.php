<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Url Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseRowUrl extends ComPagesDatabaseRowPage
{
	public function __get($column)
    {
   	 	if($column == 'type_title' && !isset($this->_data['type_title']))
		{
			$title = JText::_('URL');
			$this->_data['type_title'] = $title;
		}

		if($column == 'type_description' && !isset($this->_data['type_description'])) {
			$this->_data['type_description'] = JText::_('External Link');
		}

    	if($column == 'params_path' && !isset($this->_data['params_path']))
		{
			$path = JPATH_BASE.'/components/com_pages/databases/rows/url.xml';
			$this->_data['params_path'] = $path;
		}

    	return parent::__get($column);
   }
}