<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

/**
 * Term Controller
 *   
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Terms
 */
class ComTermsControllerTerm extends ComDefaultControllerDefault
{	
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		//Prevent state from being saved
		$this->unregisterCallback('after.browse'  , array($this, 'saveState'));
	}
}