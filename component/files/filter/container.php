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
 * Filter class for validating containers
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterContainer extends Library\FilterAbstract
{
    protected $_walk = false;

    protected function _validate($data)
    {
        if (is_string($data)) {
            return $this->getService('lib:filter.cmd')->validate($value);
        } else if (is_object($data)) {
            return true;
        }

        return false;
    }

    protected function _sanitize($data)
    {
        if (is_string($data)) {
            return $this->getService('lib:filter.cmd')->sanitize($data);
        } else if (is_object($data)) {
            return $data;
        }

        return null;
    }
}