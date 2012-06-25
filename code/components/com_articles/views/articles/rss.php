<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Articles Rss View Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */

require_once JPATH_ROOT . '/components/com_articles/helpers/route.php';

class ComArticlesViewArticlesRss extends ComArticlesViewRss
{
    public function display()
    {
        foreach ($this->getModel()->getList() as $article) {
            $this->_feed->addItem(self::getFeedItem($article));
        }

        $this->_feed->link = JRoute::_('index.php?option=com_articles&&view=articles');

        return parent::display();
    }

    /**
     * Provides a feed item given an article row.
     *
     * @param ComArticlesDatabaseRowArticle $article The article row.
     *
     * @return JFeedItem The feed item.
     */
    static public function getFeedItem(ComArticlesDatabaseRowArticle $article)
    {
        $item = new JFeedItem();

        $item->title       = $article->title;
        $item->link        = JRoute::_(ComArticlesHelperRoute::getArticleRoute($article->id, $article->category_id,$article->section_id));
        $item->description = $article->introtext . $article->fulltext;
        $item->date        = $article->created_on;
        $item->category    = $article->category_title;
        $item->author      = $article->getAuthor();

        return $item;
    }
}