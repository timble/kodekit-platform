<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Components Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */
class ComLanguagesModelComponents extends ComDefaultModelDefault
{
    public function __construct(KConfigInterface $config)
    {
        parent::__construct($config);
        
        $this->getState()
            ->remove('sort')->insert('sort', 'cmd', 'name')
            ->insert('enabled', 'boolean');
    }
    
    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns('components.name');
    }
    
    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->join(array('components' => 'components'), 'components.id = tbl.components_component_id');
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();
        
        if(!$state->isUnique())
        {
            if($state->enabled) {
                $query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
            }
            
            if($state->search) {
                $query->where('components.name LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
            }
        }
    }
}