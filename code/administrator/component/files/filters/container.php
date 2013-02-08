<?php
/**
 * @version     $Id: file.php 1041 2011-10-09 00:04:40Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Filter class for validating containers
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesFilterContainer extends KFilterAbstract
{
    protected $_walk = false;

    protected function _validate($data)
    {
        if (is_string($data)) {
            return $this->getService('koowa:filter.cmd')->validate($value);
        }
        else if (is_object($data)) {
            return true;
        }

        return false;
    }

    protected function _sanitize($data)
    {
        if (is_string($data)) {
            return $this->getService('koowa:filter.cmd')->sanitize($data);
        }
        else if (is_object($data)) {
            return $data;
        }

        return null;
    }
}