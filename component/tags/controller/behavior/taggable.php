<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Taggable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
            $row   = $context->result;
            $table = $row->getTable()->getBase();

            // Remove all existing relations
            if($row->id && $row->getTable()->getBase())
            {
                $rows = $this->getObject('com:tags.model.relations')
                    ->row($row->id)
                    ->table($table)
                    ->fetch();

                $rows->delete();
            }

            if($row->tags)
            {
                // Save tags as relations
                foreach ($row->tags as $tag)
                {
                    $relation = $this->getObject('com:tags.model.entity.relation');
                    $relation->tags_tag_id = $tag;
                    $relation->row		  = $row->id;
                    $relation->table      = $table;

                    if(!$relation->load()) {
                        $relation->save();
                    }
                }
            }

            return true;
		}
	}
	
	protected function _deleteTags(Library\ControllerContextInterface $context)
    {
        $status = $context->result->getStatus();

        if($status == Library\Database::STATUS_DELETED || $status == 'trashed')
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