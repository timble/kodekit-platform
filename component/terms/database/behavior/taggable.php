<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Terms;

use Nooku\Library;

/**
 * Taggable Database Behavior
 *   
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Terms
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
	public function getTerms()
	{
        $model = $this->getObject('com:terms.model.relations');

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
	 * If the query's where information includes a tag property, auto-join the terms tables with the query and select
     * all the rows that are tagged with the term.
	 */
	protected function _beforeTableSelect(Library\CommandContext $context)
	{
		$query = $context->query;

        if($context->query->params->has('tag'))
		{
            $table = $context->getSubject();

            $query->where('terms.slug = :tag');
            $query->where('terms_relations.table = :table')->bind(array('table' => $table->getName()));
            $query->join('terms_relations', 'terms_relations.row = tbl.'.$table->getIdentityColumn());
            $query->join('terms', 'terms.terms_term_id = terms_relations.terms_term_id');
		}
	}
}