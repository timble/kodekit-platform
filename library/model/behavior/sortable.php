<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Sortable Model Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Model
 */
class ModelBehaviorSortable extends ModelBehaviorAbstract
{
    /**
     * Insert the model states
     *
     * @param ObjectMixable $mixer
     */
    public function onMixin(ObjectMixable $mixer)
    {
        parent::onMixin($mixer);

        $mixer->getState()
            ->insert('sort', 'cmd')
            ->insert('direction', 'word', 'asc');
    }

    /**
     * Split the sort if format is [column,ASC|DESC]
     *
     * @param   ModelContextInterface $context A model context object
     * @return  void
     */
    protected function _afterReset(ModelContextInterface $context)
    {
        if($context->modified == 'sort' && strpos($context->state->sort, ',') !== false)
        {
            $context->state->sort = explode(',', $context->state->sort);

            foreach($context->state->sort as $key => $value)
            {
                if(strtoupper($value) == 'DESC' || strtoupper($value) == 'ASC')
                {
                    unset($context->state->sort[$key]);
                    $context->state->direction = $value;
                }
            }
        }
    }

    /**
     * Add order query
     *
     * @param   ModelContextInterface $context A model context object
     * @return  void
     */
    protected function _beforeFetch(ModelContextInterface $context)
    {
        $model = $context->getSubject();

        if ($model instanceof ModelDatabase && !$context->state->isUnique())
        {
            $state = $context->state;

            $sort      = $state->sort;
            $direction = strtoupper($state->direction);
            $columns   = array_keys($this->getTable()->getColumns());

            if ($sort)
            {
                $column = $this->getTable()->mapColumns($sort);
                $context->query->order($column, $direction);
            }

            if ($sort != 'ordering' && in_array('ordering', $columns)) {
                $context->query->order('tbl.ordering', 'ASC');
            }
        }
    }
}