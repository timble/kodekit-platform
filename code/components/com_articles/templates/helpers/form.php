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
 * Form Template Helper Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateHelperForm extends KTemplateHelperDefault
{
    public function publish($config = array())
    {
        $config = new KConfig($config);

        $article = $config->row;

        if ($article->isNew())
        {
            $date       = new KDate();
            $publish_up = $date->format('Y-m-d H:i:s');
        }
        else $publish_up = $article->publish_up;

        $html = JHTML::_('calendar', $publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S',
            array(
                'class'    => 'inputbox',
                'size'     => '25',
                'maxlength'=> '19'));

        return $html;
    }

    public function action($config = array())
    {
        $config = new KConfig($config);

        $article = $config->row;

        $url = '';

        if (!$article->isNew())
        {
            $url  = ComArticlesHelperRoute::getArticleRoute($article->id, $article->category_id, $article->section_id);
            $url .= '&layout=form';
        }

        return $url;
    }

    public function unpublish($config = array())
    {
        $config = new KConfig($config);

        $article = $config->row;

        if ($article->isNew() || (intval($article->publish_down) == 0)) {
            $publish_down = '';
        } else {
            $publish_down = $article->publish_down;
        }

        $html = JHTML::_('calendar', $publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S',
            array(
                'class'    => 'inputbox',
                'size'     => '25',
                'maxlength'=> '19'));

        return $html;
    }
}