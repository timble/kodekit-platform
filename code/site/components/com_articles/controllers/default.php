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
class ComArticlesControllerDefault extends ComDefaultControllerModel
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        // Set component default params values.
        /*$params = $this->getService('application')->getParams();
        $params->def('articles_per_page', 5);
        $params->def('sort_by', 'newest');
        $params->def('show_readmore', 1);
        $params->def('show_description', 0);
        $params->def('show_description_image', 0);
        $params->def('date_format', JText::_('DATE_FORMAT_LC2'));
        $params->def('show_empty_categories', 0);
        $params->def('show_cat_num_articles', 1);
        $params->def('show_category_description', 1);
        $params->def('show_feed_link', 1);
        $params->def('show_date', 1);*/
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        $access = array(0);

        if ($this->getUser()->isAuthentic()) {
            $access[] = 1;
        }

        // Filter rowsets based on current logged user's permissions.
        $request->query->access = $access;

        return $request;
    }
}