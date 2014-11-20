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
 * Taggable Database Behavior
 *   
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Tags
 */
class DatabaseBehaviorTaggable extends Library\DatabaseBehaviorAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config A ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_mixin' => true
        ));

        parent::_initialize($config);
    }

    /**
	 * Get a list of tags
	 *
	 * @return Library\DatabaseRowsetInterface
	 */
	public function getTags()
	{
        $model = $this->getObject('com:tags.model.tags');

        if(!$this->isNew())
        {
            $tags = $model->row($this->id)
                ->table($this->getTable()->getName())
                ->fetch();
        }
        else $tags = $model->fetch();

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
            $table = $context->getSubject();

            $query->where('tags.slug = :tag');
            $query->where('tags_relations.table = :table')->bind(array('table' => $table->getName()));
            $query->join('LEFT', 'tags_relations AS tags_relations', 'tags_relations.row = tbl.'.$table->getIdentityColumn());
            $query->join('LEFT', 'tags AS tags', 'tags.tags_tag_id = tags_relations.tags_tag_id');
		}
	}
}