<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Library;

/**
 * Terms Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Activities
 */
class TermsViewTermsHtml extends Library\ViewHtml
{
	public function render()
	{
		//If no row exists assign an empty array
		if($this->getModel()->get('row')) {
			$this->disabled = false;
		} else {
			$this->disabled = true;
		}
			
		return parent::render();
	}
}
