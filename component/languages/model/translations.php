<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Translations Model
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class ModelTranslations extends Library\ModelTable
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);
        
        $this->getState()
            ->insert('table', 'cmd')
            ->insert('row', 'int')
            ->insert('iso_code', 'com:languages.filter.iso')
            ->insert('status', 'int')
            ->insert('deleted', 'boolean', false);
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
            
            if($state->table) {
                $query->where('tbl.table = :table')->bind(array('table' => $state->table));
            }
            
            if($state->row) {
                $query->where('tbl.row = :row')->bind(array('row' => $state->row));
            }
            
            if($state->iso_code) {
                $query->where('tbl.iso_code = :iso_code')->bind(array('iso_code' => $state->iso_code));
            }
            
            if(!is_null($state->status)) {
                $query->where('tbl.status = :status')->bind(array('status' => $state->status));
            }
           	
            if(!is_null($state->deleted)) {
                $query->where('tbl.deleted = :deleted')->bind(array('deleted' => (int) $state->deleted));
            }
        }
    }
    
    protected function _buildQueryOrder(Library\DatabaseQuerySelect $query)
    {
        if($this->sort == 'table')
        {
            $direction = strtoupper($this->direction);
            
            $query->order('tbl.table', $direction);
      		$query->order('tbl.row', $direction);
      		$query->order('tbl.original', 'DESC');
        }
    }
}