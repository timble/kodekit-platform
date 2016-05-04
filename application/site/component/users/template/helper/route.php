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
use Kodekit\Platform\Pages;

/**
 * Route Template Helper
 *
 * @author Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Users
 */
class TemplateHelperRoute extends Pages\TemplateHelperRoute
{
    public function session($config = array())
    {
        $config   = new Library\ObjectConfig($config);
        $config->append(array(
           'layout' => null
        ));

        $needles = array(
            array('view' => 'session'),
        );

        $route = array(
            'view'     => 'session',
            'layout'   => $config->layout,
        );

        if($this->getObject('user')->isAuthentic()) {
            $route['id'] = $this->getObject('user')->getSession()->getId();
        }

        if (($page = $this->_findPage($needles))) {
            $route['Itemid'] = $page->id;
        }

        return parent::route($route);
    }

    public function user($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'access' => null,
            'layout' => null
        ));

        $route = array(
            'view'   => 'user',
            'layout' => $config->layout,
        );

        $needles = array(
            'component' => $this->getIdentifier()->package,
            'link'     => array(
                array('view' => 'user')
            )
        );

        if (isset($config->access)) {
            $needles['access'] = $config->access;
        }

        if ($page = $this->getObject('pages')->find($needles)) {
            $route['Itemid'] = $page->id;
        }

        return is_null($page) ? null : parent::route($route);
    }
}