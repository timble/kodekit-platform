<?php
/**
 * @version    	$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Modules
 * @copyright  	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license    	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link       	http://www.nooku.org
 */

/**
 * Modules Model Class
 *
 * @author		Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Modules   
 */

class ComModulesModelModules extends ComDefaultModelDefault
{
	/**
	 * A list over available modules from the filesystem
	 *
	 * @var array
	 */
	protected $_modules;

	/**
	 * A list over available module positions
	 *
	 * @var array
	 */
	protected $_positions;
	

	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->_state
			//@TODO states isn't set in helper listboxes, the client default state is a workaround
		 	->insert('client'  	, 'int', KRequest::get('get.client', 'int', 0))
		 	->insert('sort'  	, 'cmd', array('position', 'ordering'))
		 	->insert('enabled'	, 'int')
		 	->insert('position' , 'cmd')
		 	->insert('module' 	, 'cmd')
		 	->insert('assigned' , 'cmd');
	}

	protected function _buildQueryJoin(KDatabaseQuery $query)
	{
		$query
			->join('left', 'users AS user', 'user.id = tbl.checked_out')
			->join('left', 'groups AS group', 'group.id = tbl.access')
			->join('left', 'modules_menu AS module_menu', 'module_menu.moduleid = tbl.id');

		parent::_buildQueryJoin($query);
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;

		if($state->search) {
			$query->where('tbl.title', 'LIKE', '%'.$state->search.'%');
		}

		if($state->assigned)
		{
			$query
				->join('left', 'templates_menu AS template_menu', 'template_menu = module_menu.menuid')
				->where('template_menu.template', '=', $state->assigned);
		}
		
		if($state->position) {
			$query->where('tbl.position', '=', $state->position);
		}
		
		if($state->module) {
			$query->where('tbl.module', '=', $state->module);
		}

		if($state->enabled !== '' && $state->enabled !== null) {
			$query->where('tbl.published', '=', $state->enabled);
		}

		$query->where('tbl.client_id', '=', $state->client);

		parent::_buildQueryWhere($query);
	}

	/**
	 * Get the list of items based on the distinct column values
	 *
	 * We are specializing it because of the admin/site state filter
	 *
	 * @param string	The column name
	 * @return KDatabaseRowset
	 */
	public function getColumn($column)
	{	
		if (!isset($this->_column[$column])) 
		{	
			if($table = $this->getTable()) 
			{
				$query = $table->getDatabase()->getQuery()
					->distinct()
					->group('tbl.'.$table->mapColumns($column))
					->where('tbl.client_id', '=', $this->_state->client);

				$this->_buildQueryOrder($query);

				$this->_column[$column] = $table->select($query);
			}
		}
			
		return $this->_column[$column];
	}

	/**
	 * Method to get a item object which represents a table row
	 *
	 * If the model state is unique a row is fetched from the database based on the state.
	 * If not, an empty row is be returned instead.
	 *
	 * This method is customized in order to set the default module type on new rows.
	 *
	 * @return KDatabaseRow
	 */
	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = parent::getItem();

			if($this->_item->isNew() && $this->_state->module) {
				$this->_item->module = $this->_state->module;
			}
		}

		return $this->_item;
	}

	/**
	 * Gets a list over available modules
	 *
	 * @return	array
	 */
	public function getModules()
	{
		// Get the data if it doesn't already exist
		if(!isset($this->_modules))
		{
			$this->_modules	= array();
			$lang = KFactory::get('lib.joomla.language');
			$root = $this->_state->client ? JPATH_ADMINISTRATOR : JPATH_ROOT;
			$path = $root.'/modules';
		
			jimport('joomla.filesystem.folder');
			foreach(JFolder::folders($path) as $folder)
			{
				if(strpos($folder, 'mod_') === 0)
				{
					$files 				= JFolder::files( $path.'/'.$folder, '^([_A-Za-z0-9]*)\.xml$' );
					if(!$files) continue;
		
					$module				= new stdClass;
					$module->file 		= $files[0];
					$module->module 	= str_replace('.xml', '', $files[0]);
					$module->path 		= $path.'/'.$folder;
					
					$data = JApplicationHelper::parseXMLInstallFile( $module->path.'/'.$module->file);
					if($data['type'] == 'module')
					{
						$module->name			= $data['name'];
						$module->description	= $data['description'];
					}
		
					$this->_modules[]	= $module;
		
					$lang->load($module->module, $root);
				}
			}
		
			// sort array of objects alphabetically by name
			JArrayHelper::sortObjects($this->_modules, 'name' );
		}

		return $this->_modules;
	}

	/**
	 * Get a list over active module positions
	 *
	 * @return	array
	 */
	public function getPositions()
	{
		// Get the data if it doesn't already exist
		if(!isset($this->_positions))
		{
			$query = KFactory::tmp('lib.koowa.database.query')
						->distinct()
					    ->select('template')
						->where('client_id', '=', $this->_state->client);

			//@TODO if com.templates is refactored to nooku, specifying the table name is no longer necessary
			$table		= KFactory::get('admin::com.templates.database.table.menu', array('name' => 'templates_menu'));
			$templates	= $table->select($query, KDatabase::FETCH_FIELD_LIST);
			$modules	= $this->getColumn('position');
			$positions	= $modules->getColumn('position');
			$root		= $this->_state->client ? JPATH_ADMINISTRATOR : JPATH_ROOT;

			foreach($templates as $template)
			{
				$path		= $root.'/templates/'.$template.'/templateDetails.xml';

				if(!file_exists($path))					continue;
				if(!$xml = simplexml_load_file($path))	continue;
				if(!isset($xml->positions))				continue;

				foreach($xml->positions->children() as $position)
				{
					if(!in_array((string)$position, $positions)) {
						$positions[] = (string)$position;
					}
				}
			}
	
			$positions = array_unique($positions);
			sort($positions);
			
			$this->_positions = $positions;
		}

		return $this->_positions;
	}
}