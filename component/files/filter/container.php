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
 * Container Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
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