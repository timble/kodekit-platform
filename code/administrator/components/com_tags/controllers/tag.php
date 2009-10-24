<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class TagsControllerTag extends KControllerBread
{	
	public function __construct(array $options = array())
	{
		parent::__construct($options);

		//Prevent state from being saved
		$this->unregisterFilterAfter('browse'  , 'filterSaveState');
	}
	
	protected function _actionDelete() 
	{		
		$row_id 	= KRequest::get('post.row_id', 'int');
		$tag_id 	= KRequest::get('post.tags_tag_id', 'int');
		$map_id 	= KRequest::get('post.tags_map_id', 'int');
		$table_name = KRequest::get('post.table_name', 'string');
		$format 	= KRequest::get('get.format', 'string');
	
		//Delete the tag mapping
		KRequest::set('post.id', $map_id);
		KFactory::tmp('admin::com.tags.controller.map')->execute('delete');
			
		// Check for other mappings of this Tag, if none then delete the Tag
		if(!KFactory::get('admin::com.tags.model.tags')->set('tags_tag_id', $tag_id)->getTotal()){
			KRequest::set('post.id', $tag_id);
			parent::_actionDelete();
		}
		
		$this->_redirect  = 'view=tags&format='.$format.'&row_id='.$row_id.'&table_name='.$table_name;
	}
	
	protected function _actionAdd() 
	{
		$row_id 	= KRequest::get('post.row_id', 'int');
		$table_name = KRequest::get('post.table_name', 'string');
		$format 	= KRequest::get('get.format', 'string');
		
		// Get existing Tag ID
		$tags_tag_id = KFactory::tmp('admin::com.tags.model.tags')
						->set('name', KRequest::get('post.name', 'string'))->getItem()->id;
																	
		// Check if Tag exists, if not then add a new Tag and use the ID for storing in Maps table
		if(!$tags_tag_id) {
			$tags_tag_id = parent::_actionAdd()->id;
		}
		
		// Check for existing Map ID
		$tags_map_id = KFactory::tmp('admin::com.tags.model.maps')
						->set('tags_tag_id', $tags_tag_id)
						->set('table_name', $table_name)
						->set('row_id', $row_id)->getItem()->id;
						
		// Add mapping
		if(!$tags_map_id)
		{
			KRequest::set('post.id', false);
			KRequest::set('post.tags_tag_id', $tags_tag_id);
			KFactory::tmp('admin::com.tags.controller.map')->execute('add');
		}
	
		$this->_redirect = 'view=tags&format='.$format.'&row_id='.$row_id.'&table_name='.$table_name;
	}
}