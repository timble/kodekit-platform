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
 * Taggable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Tags
 */
class DatabaseBehaviorTaggable extends Library\DatabaseBehaviorAbstract
{
    /**
	 * Get a list of tags
	 *
	 * @return Library\DatabaseRowsetInterface
	 */
	public function getTags()
	{
        $package = $this->getMixer()->getIdentifier()->package;
        $model   = $this->getObject('com:tags.model.tags', array('table' => $package.'_tags'));

        if(!$this->isNew()) {
            $tags = $model->row($this->uuid)->fetch();
        } else {
            $tags = $model->fetch();
        }

        return $tags;
	}

    /**
	 * Modify the select query
	 *
	 * If the query's where information includes a tag property, auto-join the tags tables with the query and select
     * all the rows that are tagged with a term.
	 */
	protected function _beforeSelect(Library\DatabaseContext $context)
	{
		$query = $context->query;

        if($context->query->params->has('tag'))
		{
            $package = $this->getMixer()->getIdentifier()->package;

            $query->where('tags.slug = :tag');
            $query->join('LEFT', $package.'_tags_relations AS tags_relations', 'tags_relations.row = tbl.uuid');
            $query->join('LEFT', $package.'_tags AS tags', 'tags.tag_id = tags_relations.tag_id');
		}
	}
}