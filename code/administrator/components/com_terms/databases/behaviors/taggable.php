<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Terms
 * @copyright	Copyright (C) 2009 - 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Database Taggable Behavior
 *   
 * @author   	Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Terms
 */
class ComTermsDatabaseBehaviorTaggable extends KDatabaseBehaviorAbstract
{
	/**
	 * Get a list of tags
	 * 
	 * @return ComTermsRowsetTerms
	 */
	public function getTags()
	{
		$tags = $this->getService('com://admin/terms.model.terms')
					->row($this->id)
					->table($this->getTable()->getName())
					->getList();

		return $tags;
	}
        
        /**
	 * Modify the select query
	 * 
	 * If the query's where information includes a tag propery, auto-join the terms tables
	 * with the query and select all the rows that are tagged with the term.
	 */
	protected function _beforeTableSelect(KCommandContext $context)
	{
		$query = $context->query;
		
		if(!is_null($query)) 
		{
			foreach($query->where as $key => $where) 
			{	
                            if($where['property'] == 'tbl.tag') 
                                {
                                        $table = $context->caller;
                                        
					$query->where('terms_terms.slug'     , $where['constraint'],  $where['value']);
					$query->where('terms_relations.table','=', $table->getName());
					$query->join('LEFT', 'terms_relations AS terms_relations', 'terms_relations.row       = tbl.'.$table->getIdentityColumn());
					$query->join('LEFT', 'terms_terms     AS terms_terms',     'terms_terms.terms_term_id = terms_relations.terms_term_id');
				
					unset($context->query->where[$key]);
				}
			}
		}
	}
}