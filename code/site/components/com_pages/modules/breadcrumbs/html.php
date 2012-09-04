<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Breadcrumbs Module Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
 
class ComPagesModuleBreadcrumbsHtml extends ComDefaultModuleDefaultHtml
{
    public function display()
    {
        // Get the breadcrumb
        $this->assign('list'  , $this->getList($this->module->params));

        return parent::display();
    }

    function getList($params)
    {
        // Get the PathWay object from the application
        $pathway = JFactory::getApplication()->getPathway();
        $items   = $pathway->getPathWay();

        $count = count($items);
        for ($i = 0; $i < $count; $i ++)
        {
            $items[$i]->name = stripslashes(htmlspecialchars($items[$i]->name));

            if($items[$i]->link) {
                $items[$i]->link = $items[$i]->link;
            }
        }

        if ($params->get('showHome', 1))
        {
            $item = new stdClass();
            $item->name = $params->get('homeText', JText::_('Home'));

            $default = $this->getService('application.pages')->getHome();
            $item->link = $this->getRoute($default->link->getQuery().'&Itemid='.$default->id);

            array_unshift($items, $item);
        }

        return $items;
    }
} 