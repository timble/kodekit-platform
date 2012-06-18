<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * RSS template helper class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateHelperRss extends KTemplateHelperDefault
{

    public function rss($config = array()) {
        $config = new KConfig($config);

        if (!$config->url) {
            $view        = $this->getTemplate()->getView();
            $config->url = 'index.php?option=com_articles&view=' . $view->getName();
        }

        $url = $this->getService('koowa:http.url', array('url' => $config->url));

        $query           = $url->getQuery(true);
        $query['format'] = 'rss';

        $url->setQuery($query);

        $html = '<div class="articles-feed">';
        $html .= '<a title="' . JText::_('Feed entries') . '" href="' . JRoute::_($url) . '"></a>';
        $html .= '</div>';
        $html .= '<div class="clear_both"></div>';

        return $html;
    }

}