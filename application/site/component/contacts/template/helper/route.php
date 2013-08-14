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
 * Route Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Contacts
 */
class ContactsTemplateHelperRoute extends PagesTemplateHelperRoute
{
    public function contact($config = array())
	{
        $config   = new Library\ObjectConfig($config);
        $config->append(array(
            'view'     => 'contact',
            'layout'   => null,
            'category' => null
        ));

        $contact = $config->row;

        $needles = array(
            array('view' => 'contact' , 'id' => $contact->id),
            array('view' => 'category', 'id' => $contact->category),
		);

        $route = array(
            'view'     => $config->view,
            'id'       => $contact->getSlug(),
            'layout'   => $config->layout,
            'category' => $config->category
        );

		if($page = $this->_findPage($needles)) {
			$route['Itemid'] = $page->id;
		};

		return $this->getTemplate()->getView()->getRoute($route);
	}

    public function category($config = array())
    {
        $config   = new Library\ObjectConfig($config);
        $config->append(array(
            'view'   => 'contacts',
            'layout' => null
        ));

        $category = $config->row;

        $needles = array(
            array('view' => 'contacts', 'category' => $category->id),
        );

        $route = array(
            'view'     => $config->view,
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

        return $this->getTemplate()->getView()->getRoute($route);
    }
}