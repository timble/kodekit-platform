<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Containers Model
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelContainers extends Library\ModelTable
{
	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
	{
		parent::_buildQueryWhere($query);

        $state = $this->getState();
        
		if ($state->search) {
            $query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        }
	}
}