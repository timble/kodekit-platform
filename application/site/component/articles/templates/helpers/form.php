<?php
/**
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
            $publish_on = $date->format('Y-m-d H:i:s');
        }
        else $publish_on = $article->publish_on;

        $html = JHTML::_('calendar', $publish_on, 'publish_on', 'publish_on', '%Y-%m-%d %H:%M:%S',
            array(
                'class'    => 'inputbox',
                'size'     => '25',
                'maxlength'=> '19'));

        return $html;
    }

    public function unpublish($config = array())
    {
        $config = new KConfig($config);

        $article = $config->row;

        if ($article->isNew() || (intval($article->unpublish_on) == 0)) {
            $unpublish_on = '';
        } else {
            $unpublish_on = $article->unpublish_on;
        }

        $html = JHTML::_('calendar', $unpublish_on, 'unpublish_on', 'unpublish_on', '%Y-%m-%d %H:%M:%S',
            array(
                'class'    => 'inputbox',
                'size'     => '25',
                'maxlength'=> '19'));

        return $html;
    }
}