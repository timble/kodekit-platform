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
            ->insert('enabled' , 'int')
            ->insert('category', 'int')
            ->insert('sort'    , 'string', 'category');
    }
    
    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->select('c.name AS client')
              ->select('cc.title AS category')
              ->select('bannerscount.tot AS total');
    }
    
    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
        //Exclude joins if counting records
        if(!$query->count)
        {
            $query->join(
                '',
                'bannerclient AS c',
                array('c.cid = tbl.cid')
            );
            
            $query->join(
                '',
                'categories AS cc',
                array('cc.id = tbl.catid')
            );
            
            $query->join[] = array(
                'type' => 'LEFT',
                'table' => '(SELECT catid, COUNT(bid) AS tot FROM #__banner GROUP BY catid) AS bannerscount',
                'condition' => array('tbl.catid = bannerscount.catid')
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
    }
    
    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $sort       = $this->_state->sort;
        $direction  = strtoupper($this->_state->direction);

        if($sort) { 
            $query->order($this->getTable()->mapColumns($sort), $direction); 
        }

        if(array_key_exists('ordering', $this->getTable()->getColumns())) {
            $query->order('tbl.ordering', 'ASC');
        }
    }
}