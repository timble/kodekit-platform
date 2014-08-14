<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activities Database Table
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Nooku\Component\Activities
 */
class DatabaseTableActivities extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'      => 'activities',
            'behaviors' => array('creatable', 'identifiable')
        ));

        parent::_initialize($config);
    }
}