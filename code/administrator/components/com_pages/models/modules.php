<?php
/**
 * @version    	$Id: modules.php 4868 2012-08-24 14:17:38Z johanjanssens $
 * @package    	Nooku_Server
 * @subpackage 	Pages
 * @copyright  	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license    	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link       	http://www.nooku.org
 */

/**
 * Modules Model Class
 *
 * @author		Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package		Nooku_Server
 * @subpackage	Extensions
 */

class ComPagesModelModules extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('application', 'cmd', 'site')
            ->insert('component'  , 'int')
            ->insert('sort'  	  , 'cmd', array('position'))
            ->insert('enabled'	  , 'boolean')
            ->insert('position'   , 'cmd')
            ->insert('installed'  , 'boolean', false)
            ->insert('access'     , 'int')
            ->insert('page'       , 'int');
    }

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array('component_name' => 'component.name'));
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        $query
            ->join(array('user' => 'users'), 'user.id = tbl.checked_out')
            ->join(array('module_menu' => 'pages_modules_pages'), 'module_menu.modules_module_id = tbl.id')
            ->join(array('component' => 'extensions_components'), 'component.id = tbl.extensions_component_id');

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();

        if($state->search) {
            $query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        }

        if($state->position) {
            $query->where('tbl.position = :position')->bind(array('position' => $state->position));
        }

        if(is_bool($state->enabled)) {
            $query->where('tbl.published = :enabled')->bind(array('enabled' => (int) $state->enabled));
        }

        if($state->application) {
            $query->where('tbl.application = :application')->bind(array('application' => $state->application));
        }

        if($state->component) {
            $query->where('tbl.extensions_component_id = :component')->bind(array('component' => $state->component));
        }

        if (is_numeric($state->access)) {
            $query->where('tbl.access <= :access')->bind(array('access' => $state->access));
        }

        if (is_numeric($state->page)) {
            $query->where('module_menu.pages_page_id = :page OR module_menu.pages_page_id = 0')->bind(array('page' => $state->page));
        }

        parent::_buildQueryWhere($query);
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

            if($this->_item->isNew() && $this->getState()->type)
            {
                $this->_item->application = $this->getState()->application;
                $this->_item->type        = $this->getState()->type;
            }
        }

        return $this->_item;
    }

    /**
     * Get a list of items
     *
     * If the installed state is TRUE this function will return a list of the installed
     * modules.
     *
     * @return KDatabaseRowsetInterface
     */
    public function getList()
    {
        if(!isset($this->_list))
        {
            $state = $this->getState();

            if($state->installed)
            {
                $table = $this->getService('com://admin/extensions.database.table.components');
                $query = $this->getService('koowa:database.query.select')->order('name');

                $components = $table->select($query);

                // Iterate through the components.
                $modules = array();
                foreach($components as $component)
                {
                    $path  = $this->getIdentifier()->getApplication('site');
                    $path .= '/components/'.$component->name.'/modules';

                    if(!is_dir($path)) {
                        continue;
                    }

                    foreach(new DirectoryIterator($path) as $folder)
                    {
                        if($folder->isDir())
                        {
                            if(file_exists($folder->getRealPath().'/'.$folder->getFilename().'.xml'))
                            {
                                $modules[] = array(
                                    'id'                      => $folder->getFilename(),
                                    'name'                    => 'mod_'.$folder->getFilename(),
                                    'application'             => 'site',
                                    'extensions_component_id' => $component->id,
                                    'title'		              => null,
                                );
                            }
                        }
                    }
                }

                //Set the total
                $this->_total = count($modules);

                //Apply limit and offset
                if($this->getState()->limit) {
                    $modules = array_slice($modules, $state->offset, $state->limit ? $state->limit : $this->_total);
                }

                //Apply direction
                if(strtolower($state->direction) == 'desc') {
                    $modules = array_reverse($modules);
                }

                $this->_list = $this->getTable()->getRowset()->addData($modules);

            } else $this->_list = parent::getList();
        }

        return $this->_list;
    }
}