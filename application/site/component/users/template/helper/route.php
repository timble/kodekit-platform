<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Route Template Helper Class
 *
 * @author     Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package    Nooku_Server
 * @subpackage Articles
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
            $route['id'] = $this->getObject('session')->getId();
        }

        if (($page = $this->_findPage($needles))) {
            $route['Itemid'] = $page->id;
        }

		return $this->getTemplate()->getView()->getRoute($route);
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
            'extensions_extension_id' => $this->getObject('application.extensions')
                ->getExtension($this->getIdentifier()->package)->id,
            'link'                    => array(
                array('view' => 'user'))
        );

        if (isset($config->access)) {
            $needles['access'] = $config->access;
        }

        if ($page = $this->getObject('application.pages')->find($needles)) {
            $route['Itemid'] = $page->id;
        }

        return $this->getTemplate()->getView()->getRoute($route);
    }
}