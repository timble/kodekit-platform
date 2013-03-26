<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Extensions;

use Nooku\Framework;

/**
 * Select Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class TemplateHelperSelect extends Framework\TemplateHelperSelect
{
	public function booleanlist( $config = array())
	{
		$config = new Framework\Config($config);
		$config->append(array(
			'name'   	=> '',
			'attribs'	=> array(),
			'true'		=> 'yes',
			'false'		=> 'no',
			'selected'	=> null,
			'translate'	=> true
		));
		
		return parent::booleanlist($config);
	}
}