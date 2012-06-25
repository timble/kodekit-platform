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
 * Category Template Helper Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateHelperCategory extends ComArticlesTemplateHelperRss
{
    public function link($config = array())
    {
        $config   = new KConfig($config);


        $category = $config->row;

        $route = $this->getService('com://site/articles.helper.route')
                       ->getCategoryRoute($category->id, $category->section_id);

        $html = '<a href="' . JRoute::_($route) . '" class="category">' . htmlspecialchars($category->title, ENT_QUOTES) . '</a>';

        return $html;
    }

    public function totalarticles($config = array())
    {
        $config = new KConfig($config);

        $config->append(array('model_state' => array()));

        if (!is_numeric($config->total) && $config->row) {
            $config->total = $config->row->getArticles($config->model_state)->count;
        }

        $total = $config->total;

        $html = '<p>( ';
        $html .= (int) $total;
        $html .= '&nbsp;';
        $html .= ($total == 1) ? JText::_('item') : JText::_('items');
        $html .= ' )</p>';

        return $html;
    }

    public function rss($config = array())
    {
        $config = new KConfig($config);

        $category = $config->row;

        $config->url = $this->getService('com://site/articles.helper.route')
                            ->getCategoryRoute($category->id, $category->section_id);

        return parent::rss($config);
    }

}