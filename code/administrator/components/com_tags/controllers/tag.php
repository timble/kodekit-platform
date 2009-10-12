<?php
/**
 * Taxonomy
 * 
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class TagsControllerTag extends KControllerForm
{
	public function _actionBrowse()
	{
		$row_id 	= KRequest::get('get.row_id', 'int');
		$table_name = KRequest::get('get.table_name', 'string');
		
		KFactory::get('admin::com.tags.model.tags')
			->set('row_id', $row_id)
			->set('table_name', $table_name);
		
		parent::_actionBrowse();
	}
	
	public function _actionDeleteMapping() 
	{		
		$row_id 	= KRequest::get('post.row_id', 'int');
		$tag_id 	= KRequest::get('post.tags_tag_id', 'int');
		$table_name = KRequest::get('post.table_name', 'string');
		$format 	= KRequest::get('post.format', 'string');
		
		KFactory::tmp('admin::com.tags.controller.map')->execute('delete');
		
		// Check for other mappings of this Tag, if none then delete the Tag
		if(!KFactory::get('admin::com.tags.model.tags')->set('tags_tag_id', $tag_id)->getTotal()){
			KRequest::set('post.id', $tag_id);
			parent::_actionDelete();
		}
				
		$this->setRedirect('view=tags&layout='.$format.'&format='.$format.'&row_id='.$row_id.'&table_name='.$table_name);
	}
	
	public function _actionAddtag() 
	{
		$row_id 	= KRequest::get('post.row_id', 'int');
		$table_name = KRequest::get('post.table_name', 'string');
		$format 	= KRequest::get('post.format', 'string');
		
		// Get existing Tag ID
		$tag_id = KFactory::tmp('admin::com.tags.model.tags')->set('name', KRequest::get('post.name', 'string'))->getItem()->id;
		
		// Check if Tag exists, if not then add a new Tag
		if(!$tag_id){
			$row = parent::_actionSave();
			$tag_id = $row->id;
		}
		
		// Add mapping
		KRequest::set('post.id', false);
		KRequest::set('post.tags_tag_id', $tag_id);
		KFactory::tmp('admin::com.tags.controller.map')->execute('add');
		
		$this->setRedirect('view=tags&layout='.$format.'&format='.$format.'&row_id='.$row_id.'&table_name='.$table_name);
	}
}