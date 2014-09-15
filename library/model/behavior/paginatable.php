<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Paginatable Model Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
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

        if ($model instanceof ModelDatabase && !$context->state->isUnique()) {
            $state = $context->state;
            $limit = $state->limit;

            if ($limit) {
                $offset = $state->offset;
                $total  = $this->count();

                //If the offset is higher than the total recalculate the offset
                if ($offset !== 0 && $total !== 0) {
                    if ($offset >= $total) {
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