<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Taggable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Tags
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