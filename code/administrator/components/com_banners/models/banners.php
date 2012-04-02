<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners Model Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersModelBanners extends ComDefaultModelDefault 
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->getState()
            ->insert('published',   'boolean')
            ->insert('category',    'int')
            ->insert('sticky',    	'int')
            ->insert('tags',        'string');
    }
    
    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns(array('category' => 'categories.title'));
    }
    
    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        $query->join(array('categories' => 'categories'), 'categories.id = tbl.catid');
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();
        
        $query->where('categories.section LIKE :section')->bind(array('section' => 'com_banner'));
        
        if (is_bool($state->published)) {
            $query->where('tbl.showbanner = :published')->bind(array('published' => (int) $state->published));
        }
        
        if ($state->category) {
            $query->where('tbl.catid = :category')->bind(array('category' => $state->category));
        }
        
        if (is_numeric($state->sticky)) {
            $query->where('tbl.sticky = :sticky')->bind(array('sticky' => $state->sticky));
        }
        
        if (!empty($state->search)) {
            $query->where('tbl.name LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        }
        
        if (!empty($state->tags)) {
            $query->where('UPPER(tags) REGEXP :tags')
                ->bind(array('tags' => '[[:<:]]('.strtoupper(implode('|', $state->tags)).')[[:>:]]'));
        }
    }
    
    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();
        
        $sort      = $state->sort ? $state->sort : 'category';
        $direction = strtoupper($state->direction);
        
        switch ($sort)
        {
            case 'random':
                $query->order('RAND()', 'ASC');
                break;
                
            case '0':
                $query->order('tbl.sticky', 'DESC');
                $query->order('tbl.ordering', 'ASC');
                break;
                
            case 'category':
                $query->order('category', $direction);
                $query->order('tbl.ordering', 'ASC');
                break;

            default:
                $query->order($this->getTable()->mapColumns($sort), $direction); 
        }
    }
}