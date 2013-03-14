<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Framework;

/**
 * Json View
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ViewJson extends Framework\ViewJson
{
    protected function _getRow()
    {
        $row  = $this->getModel()->getRow();
        $data = parent::_getRow();

        $status = $row->getStatus() !== Framework\Database::STATUS_FAILED;
		$data['status'] = $status;
        if ($data === false){
            $data['error'] = $row->getStatusMessage();
        }

        return $data;
    }
}
