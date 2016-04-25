<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;

/**
 * Grid Template Helper
 *
 * @author   Arunas Mazeika <http://github/arunasmazeika>
 * @@package Kodekit\Platform\Users
 */
class TemplateHelperGrid extends Library\TemplateHelperGrid
{
    public function groups($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'groups' => array()
        ));

        $output = array();

        foreach ($config->groups as $group)
        {
            $href     = $this->getTemplate()->route('view=group&name=' . (int) $group);
            $output[] = '<li><a href="' . $href . '">' . StringEscaper::html($group) . '</a></li>';
        }

        return '<ul>' . implode('', $output) . '</ul>';
    }
}