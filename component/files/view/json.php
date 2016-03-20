<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Json View
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
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
