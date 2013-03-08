<?php
/**
 * @package    	Nooku_Server
 * @subpackage 	Categories
 * @copyright  	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license    	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link       	http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Categories Model Class
 *
 * @author		Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package		Nooku_Server
 * @subpackage	Categories
 */
class ComCategoriesModelCategories extends ComBaseModelDefault
{
    public function __construct(Framework\Config $config)
	{
        parent::__construct($config);

        // Set the state
        $this->getState()
            ->insert('table'     , 'string', $this->getIdentifier()->package)
            ->insert('parent'    , 'int')
            ->insert('published' , 'boolean')
            ->insert('distinct'  , 'string')
            ->insert('access'    , 'boolean')
            ->insert('category'  , 'int')
            ->insert('sort', 'cmd', 'ordering');
    }

    protected function _buildQueryJoins(Framework\DatabaseQuerySelect $query)
    {
        $state = $this->getState();
        
        //Exclude joins if counting records
        if(!$query->isCountQuery())
        {
            if ($state->table)
            {
                $query->columns(array('count'));

                $subquery = $this->getService('lib://nooku/database.query.select')
                                 ->columns(array('categories_category_id', 'count' => 'COUNT(categories_category_id)'))
                                 ->table($state->table)
                                 ->group('categories_category_id');

                $query->join(array('content' => $subquery), 'content.categories_category_id = tbl.categories_category_id');
            }
        }

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        
        $state = $this->getState();

        if($state->search) {
            $query->where('tbl.title LIKE %:search%')->bind(array('search' => $state->search));
        }

        if ($state->table) {
            $query->where('tbl.table '.(is_array($state->table) ? 'IN' : '=').' :table')->bind(array('table' => $state->table));
        }

        if (is_numeric($state->parent)) {
            $query->where('tbl.parent_id '.(is_array($state->parent) ? 'IN' : '=').' :parent')->bind(array('parent' => $state->parent));
        }
        
        if (is_numeric($state->category)) {
            $query->where('tbl.parent_id '.(is_array($state->category) ? 'IN' : '=').' :parent')->bind(array('parent' => $state->category));
        }

        if (is_bool($state->published))
        {
            $query->where('tbl.published = :published');

            if ($state->table) {
                //@TODO : com_articles doesn't have a published column need to fix this
                //$query->where('content.published = :published');
            }

            $query->bind(array('published' => (int) $state->published));
        }

        if (is_bool($state->access)) {
            $query->where('tbl.access = :access')->bind(array('access' => (int) $state->access));
        }
    }

    protected function _buildQueryGroup(Framework\DatabaseQuerySelect $query)
    {
        $state = $this->getState();
        if( $state->distinct ) 
        {
            $query->distinct();
            $query->group($state->distinct);
        } 
        else $query->group('tbl.categories_category_id');
    }
}