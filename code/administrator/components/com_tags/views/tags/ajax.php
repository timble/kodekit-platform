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

class TagsViewTags extends KViewAjax
{
	public function display($tpl = 'ajax')
	{
		$model = KFactory::get('admin::com.tags.model.tags');
		
		if(!$id = $model->get('row_id')) {
			echo JText::_("Please click 'Apply' to add tags for this profile");
			return;
		}
		
		$this->assign('tags', $model->getList());
		$this->assign('row_id', $id);
		$this->assign('table_name', $model->get('table_name'));
		$this->assign('format', 'ajax');
		parent::display($tpl);
	}

}
