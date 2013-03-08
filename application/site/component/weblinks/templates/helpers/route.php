<?php
/**
 * @package        Nooku_Server
 * @subpackage     Weblinks
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Route Template Helper Class
 *
 * @author     Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package    Nooku_Server
 * @subpackage Weblinks
 */
class ComWeblinksTemplateHelperRoute extends ComBaseTemplateHelperRoute
{
    public function weblink($config = array())
	{
        $config   = new Framework\Config($config);
        $config->append(array(
           'layout'    => null,
        ));

        $weblink = $config->row;

        $needles = array(
            array('view' => 'weblink' , 'id' => $weblink->id),
            array('view' => 'category', 'id' => $weblink->category),
		);

        $route = array(
            'view'     => 'article',
            'id'       => $weblink->getSlug(),
            'layout'   => $config->layout,
            'category' => $config->category
        );

		if($item = $this->_findPage($needles)) {
			$route['Itemid'] = $item->id;
		};

		return $this->getTemplate()->getView()->getRoute(http_build_query($route, '', '&'));
	}

    public function category($config = array())
    {
        $config   = new Framework\Config($config);
        $config->append(array(
            'layout' => null
        ));

        $category = $config->row;

        $needles = array(
            array('view' => 'weblinks', 'category' => $category->id),
        );

        $route = array(
            'view'     => 'weblinks',
            'category' => $category->getSlug(),
            'layout'   => $config->layout
        );

        if($page = $this->_findPage($needles))
        {
            if(isset($page->getLink()->query['layout'])) {
                $route['layout'] = $page->getLink()->query['layout'];
            }

            $route['Itemid'] = $page->id;
        };

        return $this->getTemplate()->getView()->getRoute(http_build_query($route, '', '&'));
    }
}