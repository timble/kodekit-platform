<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-activities for the canonical source repository
 */

namespace Kodekit\Component\Activities;

use Kodekit\Library;

/**
 * Activities Database Table.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
 */
class DatabaseTableActivities extends Library\DatabaseTableAbstract
{
    /**
     * Initializes the options for the object.
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param Library\ObjectConfig $config Configuration options.
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'      => 'activities',
            'behaviors' => array(
                'creatable',
                'identifiable',
                'parameterizable' => array('column' => 'metadata')
            ),
            'filters'   => array(
                'metadata' => 'json'
            )
        ));

        parent::_initialize($config);
    }
}
