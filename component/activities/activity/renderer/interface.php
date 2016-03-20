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
 * Activity Renderer Interface.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
 */
interface ActivityRendererInterface
{
    /**
     * Renders an activity.
     *
     * @param ActivityInterface $activity The activity object.
     * @param array             $config   An optional configuration array.
     * @return string The rendered activity.
     */
    public function render(ActivityInterface $activity, $config = array());
}