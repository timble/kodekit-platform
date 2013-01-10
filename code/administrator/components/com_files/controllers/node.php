<?php
/**
 * @version     $Id: default.php 1671 2012-05-03 03:54:38Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Node Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesControllerNode extends ComFilesControllerDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'behaviors' => array('thumbnailable')
		));

		parent::_initialize($config);
	}
}