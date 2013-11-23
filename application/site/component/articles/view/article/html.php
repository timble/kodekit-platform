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
 * Article Html View
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
 */
class ArticlesViewArticleHtml extends ArticlesViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $article = $this->getModel()->getData();

        //Set the breadcrumbs
        $pathway = $this->getObject('application')->getPathway();

        $page = $this->getObject('application.pages')->getActive();
        if($page->getLink()->query['view'] == 'categories')
        {
            $category = $this->getCategory();
            $pathway->addItem($category->title, $this->getTemplate()->getHelper('route')->category(array('row' => $category)));
            $pathway->addItem($article->title, '');
        }

        if($page->getLink()->query['view'] == 'articles') {
            $pathway->addItem($article->title, '');
        }

        return parent::_actionRender($context);
    }

    public function setData(Library\ObjectConfigInterface $data)
    {
        $article = $this->getModel()->getData();

        if ($article->id && $article->isAttachable()) {
            $data->attachments = $article->getAttachments();
        }

        if ($article->id && $article->isTaggable()) {
            $data->tags = $article->getTags();
        }

        $data->params = $this->getObject('application')->getParams();;

        return parent::setData($data);
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getObject('com:articles.model.categories')
                         ->table('articles')
                         ->id($this->getModel()->getState()->category)
                         ->getRow();

        return $category;
    }
}