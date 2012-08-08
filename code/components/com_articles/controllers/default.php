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
 * Default Controller Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesControllerDefault extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        // Set component default params values.
        $params = JComponentHelper::getParams('com_articles');
        $params->def('articles_per_page', 5);
        $params->def('show_featured', 0);
        $params->def('sort_by', 'newest');
        $params->def('show_description', 0);
        $params->def('show_description_image', 0);
        $params->def('show_page_title', 1);
        $params->def('date_format', JText::_('DATE_FORMAT_LC2'));
        $params->def('show_empty_categories', 0);
        $params->def('show_cat_num_articles', 1);
        $params->def('show_category_description', 1);
        $params->def('show_feed_link', 1);
        $params->def('show_headings', 1);
        $params->def('show_date', 1);

        parent::__construct($config);
    }

    public function setRequest(array $request)
    {
        // Filter rowsets based on current logged user's permissions.
        $request['access'] = JFactory::getUser()->get('aid', 0);

        return parent::setRequest($request);
    }

}