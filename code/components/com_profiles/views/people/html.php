<?php
/**
 * @version		$Id: html.php 215 2009-09-20 03:27:20Z johan $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesViewPeopleHtml extends ComProfilesViewHtml
{
	public function display()
	{		
		$this->assign('letters_name', $this->getModel()->getLetters());
		
		//Add RSS link
		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
		$this->_document->addHeadLink($this->createRoute('view=people&format=feed&type=rss'), 'alternate', 'rel', $attribs);
		
		//Add Atom link
		$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
		$this->_document->addHeadLink(JRoute::_('view=people&format=feed&type=atom'), 'alternate', 'rel', $attribs);

		//Display the layout
		parent::display();
	}
}