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
            ->insert('component', 'int');
    }
    
    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns(array('component_name' => 'components.name'));
    }
    
    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);
        
        $query->join(array('components' => 'extensions_components'), 'components.id = tbl.extensions_component_id');
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if(!$state->isUnique())
        {
            if(!is_null($state->enabled)) {
                $query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
            }

            if($state->component) {
                $query->where('tbl.extensions_component_id IN :component')->bind(array('component' => (array) $state->component));
            }
        }
    }
}