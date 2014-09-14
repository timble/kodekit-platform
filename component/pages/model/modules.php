<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Modules Model
 *
 * @author  Stian Didriksen <http://github.com/stipsan>
 * @package Nooku\Component\Pages
 */
class ModelModules extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('application', 'cmd', 'site')
            ->insert('component', 'alpha')
            ->insert('sort', 'cmd', 'ordering')
            ->insert('published', 'boolean')
            ->insert('position', 'cmd')
            ->insert('installed', 'boolean', false)
            ->insert('access', 'int')
            ->insert('page', 'int')
            ->insert('name', 'cmd');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('searchable'),
        ));

        parent::_initialize($config);
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        $query->join(array('module_menu' => 'pages_modules_pages'), 'module_menu.pages_module_id = tbl.pages_module_id');

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();
        if (!$state->isUnique())
        {
            if ($state->position) {
                $query->where('tbl.position = :position')->bind(array('position' => $state->position));
            }

            if (is_bool($state->published)) {
                $query->where('tbl.published = :published')->bind(array('published' => (int)$state->published));
            }

            if ($state->application) {
                $query->where('tbl.application = :application')->bind(array('application' => $state->application));
            }

            if ($state->component) {
                $query->where('tbl.component = :component')->bind(array('component' => $state->component));
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

    }

    /**
     * Method to get an object which represents a table row
     *
     * If the model state is unique a row is fetched from the database based on the state. If not, an empty row is be
     * returned instead.
     *
     * This method is customized in order to set the default module type on new rows.
     *
     * @return Library\ModelEntityInterface
     */
    protected function _actionCreate(Library\ModelContext $context)
    {
        if (!isset($this->_data))
        {
            $this->_data = parent::_actionCreate($context);

            if ($this->_data->isNew()) {
                $state = $this->getState();

                if ($state->application) {
                    $this->_data->application = $state->application;
                }

                if ($state->component) {
                    $this->_data->component = $state->component;
                }
            }
        }

        return $this->_data;
    }

    /**
     * Get a list of items
     *
     * If the installed state is TRUE this function will return a list of the installed modules.
     *
     * @return Library\ModelEntityInterface
     */
    protected function _actionFetch(Library\ModelContext $context)
    {
        $state = $context->state;

        if ($state->installed)
        {
            $modules  = array();
            $app_path = $this->getObject('object.bootstrapper')->getApplicationPath('site');
            $com_path = $app_path;

            foreach (new \DirectoryIterator($com_path) as $component)
            {
                if ($component->isDir() && substr($component, 0, 1) !== '.')
                {
                    $mod_path = $com_path . '/' . $component . '/module';

                    if (is_dir($mod_path))
                    {
                        foreach (new \DirectoryIterator($mod_path) as $folder)
                        {
                            if ($folder->isDir())
                            {
                                if (file_exists($folder->getRealPath() . '/' . $folder->getFilename() . '.xml'))
                                {
                                    $modules[] = array(
                                        'id'          => $folder->getFilename(),
                                        'name'        => $folder->getFilename(),
                                        'application' => 'site',
                                        'component'   => (string)$component,
                                        'title'       => null,
                                    );
                                }
                            }
                        }
                    }
                }
            }

            //Set the total
            $this->_count = count($modules);

            //Apply limit and offset
            if ($state->limit) {
                $modules = array_slice($modules, $state->offset, $state->limit ? $state->limit : $this->_count);
            }

            //Apply direction
            if (strtolower($state->direction) == 'desc') {
                $modules = array_reverse($modules);
            }

            $rowset = $this->getTable()->createRowset();

            foreach($modules as $module) {
                $rowset->create($module);
            }
        }
        else
        {
            if ($state->sort == 'ordering') {
                $context->query->order('position', 'ASC');
            }

            $rowset = parent::_actionFetch($context);
        }

        return $rowset;
    }
}