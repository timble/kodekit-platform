<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Database Taggable Behavior
 *
 * @author      Johan Janssens <johan@koowa.org>
 * @package     Terms
 */
class ComTermsBehaviorTaggable extends KDatabaseBehaviorAbstract
{
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
					$table = KFactory::get($context->caller);
				
					$query->where('terms_terms.slug'     , $where['constraint'],  $where['value'], '');
					$query->where('terms_relations.table','=', $table->getName());
					$query->join('LEFT', 'terms_relations AS terms_relations', 'terms_relations.row       = tbl.'.$table->getIdentityColumn());
					$query->join('LEFT', 'terms_terms     AS terms_terms',     'terms_terms.terms_term_id = terms_relations.terms_term_id');
				
					unset($context->query->where[$key]);
				}
			}
		}
	}
}