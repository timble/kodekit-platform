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

        $this->_container = $config->container;

        $this->addCommandCallback('after.add'   , '_saveTags');
        $this->addCommandCallback('after.edit'  , '_saveTags');
        $this->addCommandCallback('after.delete', '_deleteTags');
    }

    protected function _saveTags(Library\ControllerContextInterface $context)
    {
		if (!$context->response->isError())
        {
            $entity = $context->result;
            $table  = $entity->getTable()->getBase();

            // Remove all existing relations
            if($entity->id && $entity->getTable()->getBase())
            {
                $relations = $this->getObject('com:tags.model.relations')
                    ->row($entity->id)
                    ->table($table)
                    ->fetch();

                $relations->delete();
            }

            if($entity->tags)
            {
                // Save tags as relations
                foreach ($entity->tags as $tag)
                {
                    $properties = array(
                        'tags_tag_id' => $tag,
                        'row'         => $entity->id,
                        'table'       => $table
                    );

                    $relation = $this->getObject('com:tags.model.relations')
                        ->setState($properties)
                        ->fetch();

                    if($relation->isNew())
                    {
                        $relation = $this->getObject('com:tags.model.relations')->create();

                        $relation->setProperties($properties);
                        $relation->save();
                    }
                }
            }

            return true;
		}
	}
	
	protected function _deleteTags(Library\ControllerContextInterface $context)
    {
        $entity = $context->result;
        $status = $entity->getStatus();

        if($status == $entity::STATUS_DELETED || $status == 'trashed')
        {
            $id    = $context->result->get('id');
            $table = $context->result->getTable()->getBase();

            if(!empty($id) && $id != 0)
            {
                $rows = $this->getObject('com:tags.model.relations')
                    ->row($id)
                    ->table($table)
                    ->fetch();

                $rows->delete();
            }
        }
	} 
}