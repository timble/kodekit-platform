<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Containers Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesModelContainers extends ComDefaultModelDefault
{
	protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
	{
		parent::_buildQueryWhere($query);
        $state = $this->getState();
        
		if ($state->search) {
            $query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        }
	}
}