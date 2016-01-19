<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Grid Template Helper
 *
 * @author   Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @@package Component\Users
 */
class UsersTemplateHelperGrid extends Library\TemplateHelperGrid
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
            $output[] = '<li><a href="' . $href . '">' . $this->getTemplate()->escape($group) . '</a></li>';
        }

        return '<ul>' . implode('', $output) . '</ul>';
    }
}