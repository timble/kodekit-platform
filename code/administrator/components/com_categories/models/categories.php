<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Categories Table Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories    
 */
class ComCategoriesModelCategories extends ComDefaultModelDefault
{	
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->_state
		 	->insert('search'    , 'string')
			->insert('section'   , 'string')
			->insert('published' , 'int');

	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;

		/*if($state->search) {
			$query->where('title', 'LIKE',  '%'.$state->search.'%');
		}*/
		
		if($state->section) {
			$query->where('section', 'IN', $state->section);
		}

		if(is_numeric($state->published)) {
			$query->where('published', '=', $state->published);
		}
	      
		parent::_buildQueryWhere($query);
	}
}