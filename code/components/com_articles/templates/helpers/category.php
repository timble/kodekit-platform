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
 * Category template helper class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateHelperCategory extends KTemplateHelperDefault
{

    public function link($config = array()) {

        $config = new KConfig($config);

        $category = $config->row;

        $link = JRoute::_('index.php?option=com_articles&view=category&id=' . $category->id);

        $html = '<a href="' . $link . '" class="category">' . htmlspecialchars($category->title, ENT_QUOTES) . '</a>';

        return $html;
    }

    public function totalarticles($config = array()) {

        $config = new KConfig($config);

        $config->append(array('model_state' => array()));

        if (!is_numeric($config->total) && $config->row) {
            $config->total = $config->row->getTotalArticles($config->model_state);
        }

        $total = $config->total;

        $html = '<p>( ';
        $html .= (int) $total;
        $html .= '&nbsp;';
        $html .= ($total == 1) ? JText::_('item') : JText::_('items');
        $html .= ' )</p>';

        return $html;
    }

}