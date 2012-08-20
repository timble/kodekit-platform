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
 * Tables Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */
class ComLanguagesModelTables extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->getState()
            ->insert('enabled', 'boolean')
            ->insert('translated', 'boolean');
    }
    
    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);
        $state = $this->getState();
        
        if(!$state->isUnique())
        {
            if(!is_null($state->enabled)) {
                $query->join(array('components' => 'languages_components'), 'components.components_component_id = tbl.components_component_id', 'RIGHT');
            }
        }
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();
        
        if(!$state->isUnique())
        {
            if(!is_null($state->enabled)) {
                $query->where('components.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
            }
        }
    }
}