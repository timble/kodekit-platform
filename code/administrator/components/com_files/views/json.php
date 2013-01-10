<?php
/**
 * @version     $Id$
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
    protected function _getItem()
    {
        $row = $this->getModel()->getItem();

        $output = parent::_getItem();

        $status = $row->getStatus() !== KDatabase::STATUS_FAILED;
		$output['status'] = $status;
        if ($status === false){
            $output['error'] = $row->getStatusMessage();
        }

        return $output;
    }
}
