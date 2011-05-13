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
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->_state
		 	->insert('application', 'cmd', 'site')
		 	->insert('sort'  	  , 'cmd', array('position', 'ordering'))
		 	->insert('enabled'	  , 'int')
		 	->insert('position'   , 'cmd')
		 	->insert('module' 	  , 'cmd')
		 	->insert('assigned'   , 'cmd')
		 	->insert('new'        , 'boolean', false);
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

		$query->where('tbl.client_id', '=', (int)($state->application == 'admin'));

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
					->where('tbl.client_id', '=', (int)($this->_state->application == 'admin'));

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
     * Get a list of items
     *
     * @return KDatabaseRowsetInterface
     */
    public function getList()
    { 
        if(!isset($this->_list))
        {
            if($this->_state->new)
            {
                $list = array();
            	$lang = KFactory::get('lib.joomla.language');
            	$root = $this->_state->application == 'admin' ? JPATH_ADMINISTRATOR : JPATH_ROOT;
            	$path = $root.'/modules';
            	$this->_list = $this->getTable()->getRowset();
            
            	jimport('joomla.filesystem.folder');
            	foreach(JFolder::folders($path) as $i => $folder)
            	{
            		if(strpos($folder, 'mod_') === 0)
            		{
            			$files 				= JFolder::files( $path.'/'.$folder, '^([_A-Za-z0-9]*)\.xml$' );
            			if(!$files) continue;
            
            			$module				= array();
            			//The rowset wont add rows without an id to it
            			$module['id']       = $this->getTotal() + $i;
            			$module['file'] 		= $files[0];
            			$module['module'] 	= str_replace('.xml', '', $files[0]);
            			$module['path'] 		= $path.'/'.$folder;
            			
            			$data = JApplicationHelper::parseXMLInstallFile( $module['path'].'/'.$module['file']);
            			if($data['type'] == 'module')
            			{
            				$module['name']			= $data['name'];
            				$module['description']	= $data['description'];
            			}
            
                        
            			$list[]	= $module;            			
            
            			$lang->load($module['module'], $root);
            		}
            	}
            	$this->_list->addData($list);
            	// sort array of objects alphabetically by name
            	//JArrayHelper::sortObjects($this->_list, 'name' );
            	
            	//$this->_list = KFactory::tmp('admin::com.modules.database.rowset.modules', array('data' => $data));
            }
            else {
                $this->_list = parent::getList();
            }
        }

        return $this->_list;
    }
}