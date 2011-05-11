<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * File Filter Class
 *
 * @author      Ercan …zkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages   
 */

jimport('joomla.filesystem.file');

class ComLanguagesFilterSafefile extends KFilterCmd 
{
	protected function _sanitize($value)
    {
		$value = parent::_sanitize($value);
		
		$value = JFile::makeSafe($value);
		return $value;
	}
}