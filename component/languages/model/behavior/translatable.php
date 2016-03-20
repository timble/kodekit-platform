<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Translatable Model Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Languages
 */
class ModelBehaviorTranslatable extends Library\ModelBehaviorAbstract
{
    protected function _beforeFetch(Library\ModelContextInterface $context)
    {
        $model = $context->getSubject();

        if ($model instanceof Library\ModelDatabase && $model->getTable()->isTranslatable()) {
            $state = $model->getState();
            if (!isset($state->translated)) {
                $state->insert('translated', 'boolean');
            }
        }
    }
}