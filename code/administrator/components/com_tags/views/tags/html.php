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

class TagsViewTags extends KViewDefault
{
	public function display()
	{
		// Mixin a menubar object
		$this->mixin( KFactory::get('admin::com.tags.mixin.menu', array('mixer' => $this)));
		$this->displayMenutitle();
		$this->displayMenubar();

		//Create the toolbar
		KFactory::get('admin::com.tags.toolbar.tags');

		//Display the layout
		parent::display();
	}
}