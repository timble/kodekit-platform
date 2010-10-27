<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Revision Row
.*
 * @author      Torkil Johnsen <torkil@bedre.no>
 * @author      Johan Janssens <johan@timble.net>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 */
class ComVersionsDatabaseRowRevision extends KDatabaseRowDefault
{
	public function __get($column)
    {
    	if($column == 'data' && is_string($this->_data['data'])) {
			$this->_data['data'] = json_decode($this->_data['data'], true);
		}

    	return parent::__get($column);
   }
}