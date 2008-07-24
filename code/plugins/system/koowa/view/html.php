<?php
/**
 * @version		$Id$
 * @package     Koowa_View
 * @subpackage  Html
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View HTML Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_View
 * @subpackage  Html
 */

class KViewHtml extends KViewAbstract
{
	public function display($tpl = null)
	{
		$prefix = $this->getClassName('prefix');

		//Set the main stylesheet for the component
		KViewHelper::_('stylesheet', "$prefix.css", "media/com_$prefix/css/");

		parent::display($tpl);
	}
}
