<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Page Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesViewPageHtml extends ComDefaultViewHtml
{
	public function display()
	{
		$language	= KFactory::get('lib.koowa.language');
		$components	= $this->getModel()->getComponents();
		
		foreach($components as $component) {
			$language->load($component->option, JOOMLA_PATH.'/administrator');
		}
		
		$this->assign('live_site', KFactory::get('lib.koowa.settings')->joomla_url);

		return parent::display();
	}
}