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
 * Component Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseRowComponent extends ComPagesDatabaseRowPage
{
	public function __get($column)
    {
		if($column == 'type_description' && !isset($this->_data['type_description']))
		{
			$query			= KFactory::tmp('lib.koowa.http.url', array('url' => $this->_data['link']))->query;
			$description	= $this->component_name ? $this->component_name : ucfirst(substr($query['option'], 4));

			if (isset($query['view'])) {
				$description .= ' &raquo; '.JText::_(ucfirst($query['view']));
			}

			if (isset($query['layout'])) {
				$description .= ' / '.JText::_(ucfirst($query['layout']));
			}
			
			$this->_data['type_description'] = $description;
		}

   	 	if($column == 'type_title' && !isset($this->_data['type_title']))
		{
			$title = JText::_('Component');
			$this->_data['type_title'] = $title;
		}

    	return parent::__get($column);
   }
}