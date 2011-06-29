<?php
/**
 * @version     $Id: json.php 2074 2011-06-28 13:19:44Z ercanozkaya $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Nodes Json View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesViewJson extends KViewJson
{
    public function display()
    {
    	$model = $this->getModel();

        if(KInflector::isPlural($this->getName())) {
            $data = $this->_getList($model);
        } else {
            $data = $this->_getItem($model);
        }

        $this->output = $data;

        return parent::display();
    }

    protected function _getList(KModelAbstract $model)
    {
        $list  = array_values($model->getList()->toArray());
        $state = $model->getState();
        $total = $model->getTotal();

        $output = new stdclass;
        $output->total = $total;
        $output->limit = $state->limit;
        $output->offset = $state->offset;
        $output->items = $list;

        return $output;
    }

    protected function _getItem(KModelAbstract $model)
    {
        $row = $model->getItem();

        $output = new stdclass;
        $output->status = $row->getStatus() !== KDatabase::STATUS_FAILED && $row->path;

        if ($output->status !== false)
        {
            $output->item = $row->toArray();
        }
        else $output->error = $row->getStatusMessage();

        return $output;
    }
}
