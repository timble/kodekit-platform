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
        $list   = (array) $this->getService('application')->getPathway()->items;
        $params = $this->module->params;

        if($params->get('showHome', 1))
        {
            $item = new stdClass();
            $item->name = $params->get('homeText', JText::_('Home'));

            $home = $this->getService('application.pages')->getHome();
            $item->link = $this->getRoute($home->getLink()->getQuery().'&Itemid='.$home->id);

            array_unshift($list, $item);
        }

        $this->assign('list', $list);

        return parent::display();
    }
} 