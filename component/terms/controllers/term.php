<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Terms;

use Nooku\Framework;

/**
 * Term Controller
 *   
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Terms
 */
class ControllerTerm extends \ComBaseControllerDefault
{	
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		//Prevent state from being saved
		$this->unregisterCallback('after.browse'  , array($this, 'saveState'));
	}
}