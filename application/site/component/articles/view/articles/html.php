<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Articles Html View
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
 */
class ArticlesViewArticlesHtml extends ArticlesViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        //Get the parameters
        $params = $this->getObject('application.pages')->getActive()->getParams('page');

        //Get the category
        $category = $this->getCategory();

        //Set the pathway
        $page = $this->getObject('application.pages')->getActive();
        if ($page->getLink()->query['view'] == 'categories') {
            $this->getObject('application')->getPathway()->addItem($category->title, '');
        }

        $context->data->params   = $params;
        $context->data->category = $category;

        parent::_fetchData($context);
    }

    public function getCategory()
    {
        $category = $this->getObject('com:articles.model.categories')
            ->table('articles')
            ->id($this->getModel()->getState()->category)
            ->fetch();

        return $category;
    }

    public function highlight($text)
    {
        if ($search = $this->getModel()->getState()->search) {
            $text = preg_replace('/' . $search . '(?![^<]*?>)/i', '<span class="highlight">' . $search . '</span>', $text);
        }

        return $text;
    }
}