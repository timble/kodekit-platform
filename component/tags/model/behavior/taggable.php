<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-tags for the canonical source repository
 */

namespace Kodekit\Component\Tags;

use Kodekit\Library;

/**
 * Taggable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Tags
 */
class ModelBehaviorTaggable extends Library\ModelBehaviorAbstract
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('before.create' , '_makeTaggable');
        $this->addCommandCallback('before.fetch'  , '_makeTaggable');
    }

    public function onMixin(Library\ObjectMixable $mixer)
    {
        parent::onMixin($mixer);

        //Insert the tag model state
        $mixer->getState()->insert('tag', 'slug');
    }

    protected function _makeTaggable(Library\ModelContextInterface $context)
    {
        //Add the taggable behavior to the table
        $model = $context->getSubject();
        $model->getTable()->addBehavior('com:tags.database.behavior.taggable');
    }

    protected function _beforeFetch(Library\ModelContextInterface $context)
    {
        $model = $context->getSubject();

        if ($model instanceof Library\ModelDatabase)
        {
            $state = $context->state;

            if ($state->tag) {
                $context->query->bind(array('tag' => $state->tag));
            }
        }
    }
}