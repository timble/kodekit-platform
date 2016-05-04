<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-tags for the canonical source repository
 */

namespace Kodekit\Component\Tags;

use Kodekit\Library;

/**
 * Taggable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Tags
 */
class ControllerBehaviorTaggable extends Library\BehaviorAbstract
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('after.add'    , '_setTags');
        $this->addCommandCallback('after.edit'   , '_setTags');
        $this->addCommandCallback('after.delete' , '_removeTags');
    }

    protected function _setTags(Library\ControllerContextInterface $context)
    {
        $entity = $context->result;

        if ($entity->isIdentifiable() && !$context->response->isError())
        {
            $tags   = $entity->getTags();

            $package = $this->getMixer()->getIdentifier()->package;
            if(!$this->getObject('com:'.$package.'.controller.tag')->canAdd()) {
                $status  = Library\Database::STATUS_FETCHED;
            } else {
                $status = null;
            }

            //Delete tags
            if(count($tags))
            {
                $tags->delete();
                $tags->clear();
            }

            //Create tags
            if($entity->tags)
            {
                foreach ($entity->tags as $tag)
                {
                    $properties = array(
                        'title' => $tag,
                        'row'   => $entity->uuid,
                    );

                    $tags->insert($properties, $status);
                }
            }

            $tags->save();

            return true;
        }
    }

    protected function _removeTags(Library\ControllerContextInterface $context)
    {
        $entity = $context->result;
        $status = $entity->getStatus();

        if($entity->isIdentifiable() && $status == $entity::STATUS_DELETED) {
            $entity->getTags()->delete();
        }
    }
}