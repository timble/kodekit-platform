<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
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
        
        $this->_state
            ->insert('enabled',     'int')
            ->insert('category',    'int')
            ->insert('tags',        'string');
    }
    
    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);
        
        $query
            ->select('cc.title AS category');
    }
    
    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
        //Exclude joins if counting records
        if(!$query->count)
        {
            $query->join(
                '',
                'categories AS cc',
                array('cc.id = tbl.catid')
            );
                        
            $query->where('cc.section', 'LIKE', 'com_banner');
        }
    }
    
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);
        
        $state = $this->_state;
        
        if (is_numeric($state->enabled)) {
            $query->where('tbl.showbanner', '=', $state->enabled);
        }
        
        if ($state->category) {
            $query->where('tbl.catid', '=', $state->category);
        }
        
        if (!empty($state->search)) {
            $query->where('LOWER(tbl.name)', 'LIKE', '%'.strtolower($state->search).'%');
        }
        
        if (!empty($state->tags)) {
            $where = array();
            foreach ($state->tags as $tag) {
                $where[] = 'UPPER(tags) REGEXP \'[[:<:]]'.strtoupper(trim($tag)).'[[:>:]]\'';
            }
            $query->where('( '.implode(' OR ', $where).' )');
        }
    }
    
    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $sort       = $this->_state->sort ? $this->_state->sort : 'category';
        $direction  = strtoupper($this->_state->direction);
        
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