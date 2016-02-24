<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Taggable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Tags
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