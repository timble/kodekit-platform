<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * View HTML Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksViewWeblinksHtml extends ComDefaultViewHtml
{
	public function display()
	{
		$this->getToolbar()
			->setTitle(JText::_('Weblinks'))
			->append('divider')
			->append('enable')
			->append('disable')
			->append('divider')
			->append('preferences');

		return parent::display();
	}
}