<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Modules Model
 *
 * @author  Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package Nooku\Component\Pages
 */
class ModelModules extends Library\ModelTable
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('application', 'cmd', 'site')
            ->insert('extension'  , 'int')
            ->insert('sort'  	  , 'cmd', 'ordering')
            ->insert('published'  , 'boolean')
            ->insert('position'   , 'cmd')
            ->insert('installed'  , 'boolean', false)
            ->insert('access'     , 'int')
            ->insert('page'       , 'int')
            ->insert('name'       , 'cmd');
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array('extension_name' => 'extensions.name'));
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        $query
            ->join(array('module_menu' => 'pages_modules_pages'), 'module_menu.pages_module_id = tbl.pages_module_id')
            ->join(array('extensions'  => 'extensions'), 'extensions.extensions_extension_id = tbl.extensions_extension_id');

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
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

            if($state->extension) {
                $query->where('tbl.extensions_extension_id = :extension')->bind(array('extension' => $state->extension));
            }

            if (is_numeric($state->access)) {
                $query->where('tbl.access <= :access')->bind(array('access' => $state->access));
            }

            if (is_numeric($state->page)) {
                $query->where('module_menu.pages_page_id IN :page')->bind(array('page' => array($state->page, 0)));
            }
        }
    }

    protected function _buildQueryOrder(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        $direction = strtoupper($state->direction);

        if ($state->sort == 'ordering')
        {
            $query->order('position', 'ASC')
                ->order('ordering', $direction);
        }
        else
        {
            $query->order($state->sort, $direction)
                ->order('ordering', 'ASC');
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
     * @return Library\DatabaseRow
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

                if($state->extension)
                {
                    $this->_row->extensions_extension_id = $state->extension;

                    $this->_row->extension_name = $this->getObject('application.extensions')
                        ->find(array('id' => $state->extension))
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
     * If the installed state is TRUE this function will return a list of the installed modules.
     *
     * @return Library\DatabaseRowsetInterface
     */
    public function getRowset()
    {
        if(!isset($this->_rowset))
        {
            $state = $this->getState();

            if($state->installed)
            {
                $table = $this->getObject('com:extensions.database.table.extensions');
                $query = $this->getObject('lib:database.query.select')->order('name');

                $extension = $table->select($query);

                // Iterate through the extension
                $modules = array();
                foreach($extension as $extension)
                {
                    $path  = Library\ClassLoader::getInstance()->getApplication('site');
                    $path .= '/component/'.substr($extension->name, 4).'/modules';

                    if(!is_dir($path)) {
                        continue;
                    }

                    foreach(new \DirectoryIterator($path) as $folder)
                    {
                        if($folder->isDir())
                        {
                            if(file_exists($folder->getRealPath().'/'.$folder->getFilename().'.xml'))
                            {
                                $modules[] = array(
                                    'id'                      => $folder->getFilename(),
                                    'name'                    => 'mod_'.$folder->getFilename(),
                                    'application'             => 'site',
                                    'extensions_extension_id' => $extension->id,
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