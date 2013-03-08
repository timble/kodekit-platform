<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Activities Database Table
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Activities
 */
class ComActivitiesDatabaseTableActivities extends Framework\DatabaseTableDefault
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'behaviors' => array('creatable', 'identifiable')
        ));

        parent::_initialize($config);
    }
}