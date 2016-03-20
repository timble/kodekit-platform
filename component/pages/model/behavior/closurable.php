<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Closurable Controller Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Pages
 */
class ModelBehaviorClosurable extends Library\ModelBehaviorAbstract
{
    protected function _beforeFetch(Library\ModelContextInterface $context)
    {
        $model = $context->getSubject();

        if ($model instanceof Library\ModelDatabase && $model->getTable()->isClosurable())
        {
            $state = $context->state;

            if (!isset($state->parent)) {
                $state->insert('parent', 'int');
            }

            if (!isset($state->level)) {
                $state->insert('level', 'int');
            }

            if (!$state->isUnique())
            {
                if ($state->sort) {
                    $context->query->bind(array('sort' => $state->sort));
                }

                if ($state->parent) {
                    $context->query->bind(array('parent' => $state->parent));
                }

                if ($state->level) {
                    $context->query->bind(array('level' => $state->level));
                }
            }
        }
    }
}