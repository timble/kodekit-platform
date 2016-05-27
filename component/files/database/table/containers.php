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
 * Containers Database Table
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class DatabaseTableContainers extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'filters' => array(
                'slug' 	     => 'cmd',
                'path'       => 'com:files.filter.path',
                'parameters' => 'json'
            ),
            'behaviors' => array(
                'sluggable' => array('columns' => array('id', 'title')),
                'identifiable', 'parameterizable'
            )
        ));

        parent::_initialize($config);
    }
}
