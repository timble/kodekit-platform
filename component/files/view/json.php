<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Json View
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ViewJson extends Library\ViewJson
{
    protected function _renderData()
    {
        $output = parent::_renderData();

        if (!$this->isCollection())
        {
            $entity = $this->getModel()->fetch();
            $status = $entity->getStatus() !== $entity::STATUS_FAILED;

            $output['status'] = $status;

            if ($status === false) {
                $output['error'] = $entity->getStatusMessage();
            }
        }

        return $output;
    }
}
