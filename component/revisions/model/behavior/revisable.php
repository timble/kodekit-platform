<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-revisions for the canonical source repository
 */

namespace Kodekit\Component\Revisions;

use Kodekit\Library;

/**
 * Revisable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Revisions
 */
class ModelBehaviorRevisable extends Library\ModelBehaviorAbstract
{
    public function onMixin(Library\ObjectMixable $mixer)
    {
        parent::onMixin($mixer);

        $mixer->getState()
            ->insert('trashed', 'int');
    }

    protected function _beforeFetch(Library\ModelContextInterface $context)
    {
        $model = $context->getSubject();

        if ($model instanceof Library\ModelDatabase && $this->getTable()->isRevisable())
        {
            $state = $context->state;

            if ($state->trashed) {
                $context->query->bind(array('deleted' => 1));
            }
        }
    }
}