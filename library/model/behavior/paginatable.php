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
 * Paginatable Model Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
 */
class ModelBehaviorPaginatable extends ModelBehaviorAbstract
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
            ->insert('limit', 'int')
            ->insert('offset', 'int');
    }

    /**
     * Get the model paginator object
     *
     * @return  ModelPaginator  The model paginator object
     */
    public function getPaginator()
    {
        $paginator = new ModelPaginator(array(
            'offset' => (int)$this->getState()->offset,
            'limit'  => (int)$this->getState()->limit,
            'total'  => (int)$this->count(),
        ));

        return $paginator;
    }

    /**
     * Add limit query
     *
     * @param   ModelContextInterface $context A model context object
     *
     * @return    void
     */
    protected function _beforeFetch(ModelContextInterface $context)
    {
        $model = $context->getSubject();

        if ($model instanceof ModelDatabase && !$context->state->isUnique())
        {
            $state = $context->state;
            $limit = $state->limit;

            if ($limit)
            {
                $offset = $state->offset;
                $total  = $this->count();

                //If the offset is higher than the total recalculate the offset
                if ($offset !== 0 && $total !== 0)
                {
                    if ($offset >= $total)
                    {
                        $offset        = floor(($total - 1) / $limit) * $limit;
                        $state->offset = $offset;
                    }
                }

                $context->query->limit($limit, $offset);
            }
        }
    }

    /**
     * Recalculate offset
     *
     * @param   ModelContextInterface $context A model context object
     *
     * @return    void
     */
    protected function _afterReset(ModelContextInterface $context)
    {
        $modified = (array) ObjectConfig::unbox($context->modified);
        if (in_array('limit', $modified))
        {
            $limit = $context->state->limit;

            if ($limit) {
                $context->state->offset = floor($context->state->offset / $limit) * $limit;
            }
        }
    }
}