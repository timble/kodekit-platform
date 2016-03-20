<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Tables Model
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Languages
 */
class ModelTables extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('enabled'  , 'boolean')
            ->insert('component', 'alpha');
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if(!$state->isUnique())
        {
            if(!is_null($state->enabled)) {
                $query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
            }

            if($state->component) {
                $query->where('tbl.component IN :component')->bind(array('component' => (array) $state->component));
            }
        }
    }
}