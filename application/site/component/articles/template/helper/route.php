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
 * @package Component\Articles
 */
class ArticlesTemplateHelperRoute extends PagesTemplateHelperRoute
{
	public function article($config = array())
	{
        $config   = new Library\ObjectConfig($config);
        $config->append(array(
            'view'   => 'article',
            'layout' => null,
            'format'    => 'html'
        ));

        $article = $config->row;

        // TODO: I think that instead of the categories_category_id we should use the category parent
        $needles = array(
            array('view' => 'article' , 'id' => $article->id),
            array('view' => 'category', 'id' => $article->categories_category_id)
		);

        $route = array(
            'view'     => $config->view,
            'id'       => $article->getSlug(),
            'layout'   => $config->layout,
            'category' => $config->category,
            'format'    => $config->format
        );

        if (($page = $this->_findPage($needles)) || ($article->isPageable() && ($page = $article->getPage()))) {
            $route['Itemid'] = $page->id;
        }

		return $this->getTemplate()->getView()->getRoute($route);
	}

    public function category($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'view'   => 'articles',
            'layout' => 'table'
        ));

        $category = $config->row;

        $needles = array(
            array('view' => 'category'   , 'id' => $category->id),
        );

        $route = array(
            'view'      => $config->view,
            'category'  => $category->getSlug(),
            'layout'    => $config->layout
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