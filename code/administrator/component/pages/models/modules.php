<?php
/**
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
            ->insert('published'  , 'boolean')
            ->insert('position'   , 'cmd')
            ->insert('installed'  , 'boolean', false)
            ->insert('access'     , 'int')
            ->insert('page'       , 'int')
            ->insert('name'       , 'cmd');
    }

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array('component_name' => 'components.name'));
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        $query
            ->join(array('module_menu' => 'pages_modules_pages'), 'module_menu.modules_module_id = tbl.id')
            ->join(array('components' => 'extensions_components'), 'components.extensions_component_id = tbl.extensions_component_id');

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();
        if(!$state->isUnique())
        {
            if($state->search) {
                $query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
            }

            if($state->position) {
                $query->where('tbl.position = :position')->bind(array('position' => $state->position));
            }

            if(is_bool($state->published)) {
                $query->where('tbl.published = :published')->bind(array('published' => (int) $state->published));
            }

            if($state->application) {
                $query->where('tbl.application = :application')->bind(array('application' => $state->application));
            }

            if($state->component) {
                $query->where('tbl.extensions_component_id = :component')->bind(array('component' => $state->component));
            }

            if (is_numeric($state->access)) {
                $query->where('tbl.access = :access')->bind(array('access' => $state->access));
            }

            if (is_numeric($state->page)) {
                $query->where('module_menu.pages_page_id IN :page')->bind(array('page' => array($state->page, 0)));
            }
        }
    }

    /**
     * Method to get an object which represents a table row
     *
     * If the model state is unique a row is fetched from the database based on the state. If not, an empty row is be
     * returned instead.
     *
     * This method is customized in order to set the default module type on new rows.
     *
     * @return KDatabaseRow
     */
    public function getRow()
    {
        if(!isset($this->_row))
        {
            $this->_row = parent::getRow();

            if($this->_row->isNew())
            {
                $state = $this->getState();

                if($state->application) {
                    $this->_row->application = $state->application;
                }

                if($state->component)
                {
                    $this->_row->extensions->component_id = $state->component;

                    $this->_row->component_name = $this->getService('application.components')
                        ->find(array('id' => $state->component))
                        ->top()
                        ->name;
                }
            }
        }

        return $this->_row;
    }

    /**
     * Get a list of items
     *
     * If the installed state is TRUE this function will return a list of the installed
     * modules.
     *
     * @return KDatabaseRowsetInterface
     */
    public function getRowset()
    {
        if(!isset($this->_rowset))
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
                    $path .= '/component/'.substr($component->name, 4).'/modules';

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

                $this->_rowset = $this->getTable()->getRowset()->addRow($modules);

            } else $this->_rowset = parent::getRowset();
        }

        return $this->_rowset;
    }
}