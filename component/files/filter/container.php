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
 * Container Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class FilterContainer extends Library\FilterAbstract
{
    public function validate($data)
    {
        if (is_string($data)) {
            return $this->getObject('lib:filter.cmd')->validate($data);
        } else if (is_object($data)) {
            return true;
        }

        return false;
    }

    public function sanitize($data)
    {
        if (is_string($data)) {
            return $this->getObject('lib:filter.cmd')->sanitize($data);
        } else if (is_object($data)) {
            return $data;
        }

        return null;
    }
}