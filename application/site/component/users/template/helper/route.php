<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Route Template Helper
 *
 * @author Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Users
 */
class UsersTemplateHelperRoute extends PagesTemplateHelperRoute
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

		return $this->getTemplate()->route($route);
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
                array('view' => 'user'))
        );

        if (isset($config->access)) {
            $needles['access'] = $config->access;
        }

        if ($page = $this->getObject('application.pages')->find($needles)) {
            $route['Itemid'] = $page->id;
        }

        return is_null($page) ? null : $this->getTemplate()->route($route);
    }
}