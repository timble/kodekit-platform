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
 * Activity Renderer Interface.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
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