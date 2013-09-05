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
 * Taggable Database Behavior
 *   
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Tags
 */
class DatabaseBehaviorTaggable extends Library\DatabaseBehaviorAbstract
{
	/**
	 * Get a list of tags
	 * 
	 * @return DatabaseRowsetTags
	 */
	public function getTags()
	{
        $model = $this->getObject('com:tags.model.relations');

        if(!$this->isNew())
        {
            $tags = $model->row($this->id)
                ->table($this->getTable()->getName())
                ->getRowset();
        }
        else $tags = $model->getRowset();

        return $tags;
	}
        
    /**
	 * Modify the select query
	 * 
	 * If the query's where information includes a tag propery, auto-join the tags tables with the query and select
     * all the rows that are tagged with a term.
	 */
	protected function _beforeTableSelect(Library\CommandContext $context)
	{
		$query = $context->query;
		
		if(!is_null($query)) 
		{
            foreach($query->where as $key => $where) 
			{	
                if($where['condition'] == 'tbl.tag') 
                {
                    $table = $context->caller;
                                        
					$query->where('tags.slug', $where['constraint'],  $where['value']);
					$query->where('tags_relations.table','=', $table->getName());
					$query->join('LEFT', 'tags_relations AS tags_relations', 'tags_relations.row = tbl.'.$table->getIdentityColumn());
					$query->join('LEFT', 'tags AS tags', 'tags.tags_tag_id = tags_relations.tags_tag_id');
				
					unset($context->query->where[$key]);
				}
			}
		}
	}
}