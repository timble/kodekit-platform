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
 * Section template helper class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateHelperSection extends ComArticlesTemplateHelperRss
{

    public function rss($config = array()) {

        $config = new KConfig($config);

        $section = $config->row;

        $config->url = ComArticlesHelperRoute::getSectionRoute($section->id);

        return parent::rss($config);
    }

}