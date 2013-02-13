<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Nodes Json View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesViewJson extends KViewJson
{
    protected function _getRow()
    {
        $row = $this->getModel()->getRow();

        $data = parent::_getRow();

        $status = $row->getStatus() !== KDatabase::STATUS_FAILED;
		$data['status'] = $status;
        if ($data === false){
            $data['error'] = $row->getStatusMessage();
        }

        return $data;
    }
}
