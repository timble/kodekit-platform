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
 * RSS View Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewRss extends KViewTemplate
{
    /**
     * @var KConfig Configuration parameters to be sent to the feed
     * renderer (see com://site/articles.template.helper.rss).
     */
    protected $_rss_config;

    public function __construct(KConfig $config) {
        parent::__construct($config);

        $state = $this->getModel()->getState();

        $this->_rss_config = $config->rss_config;

        // Remove pagination constraints.
        unset($state->limit);
        unset($state->offset);
    }

    protected function _initialize(KConfig $config) {
        $config->append(array(
            'mimetype'   => 'application/rss+xml', 'rss_config' => array()));
        parent::_initialize($config);
    }

    public function display() {

        $config = clone $this->_rss_config;
        // Append the feed items.
        $config->append(array('channel' => array('items' => clone $this->_getItems())));

        $this->assign('config', $config->toArray());

        return parent::display();
    }

    /**
     * Feed items getter.
     *
     * OVERRIDE THIS METHOD IF YOU NEED TO GET A DIFFERENT LIST OF ITEMS.
     *
     * @return ComArticlesDatabaseRowsetArticles The feed items.
     */
    protected function _getItems() {
        return $this->_prepareItems($this->getModel()->getList());
    }

    /**
     * Method for setting/overriding fields to be included in the feed.
     *
     * OVERRIDE THIS METHOD IF YOU ARE EXTENDING THIS CLASS ON ANOTHER COMPONENT.
     *
     * @param ComArticlesDatabaseRowsetArticles $items The original items.
     *
     * @return ComArticlesDatabaseRowsetArticles The new items.
     */
    protected function _prepareItems(ComArticlesDatabaseRowsetArticles $items) {
        foreach ($items as $item) {
            $item->link        = JRoute::_($this->getService('com://site/articles.helper.route')
                ->getArticleRoute($item->id, $item->category_id, $item->section_id));
            $item->guid        = $item->link;
            $item->category    = $item->category_title;
            $item->description = $item->introtext . $item->fulltext;
        }
        return $items;
    }

    public function setLayout($layout) {
        // Layout override.
        $this->_layout = $this->getIdentifier('com://site/articles.view.articles.rss');
        return $this;
    }
}