<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Json View
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ViewJson extends Library\ViewJson
{
    protected function _getRow()
    {
        $row  = $this->getModel()->getRow();
        $data = parent::_getRow();

        $status = $row->getStatus() !== Library\Database::STATUS_FAILED;
		$data['status'] = $status;
        if ($data === false){
            $data['error'] = $row->getStatusMessage();
        }

        return $data;
    }
}
