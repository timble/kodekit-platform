<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Breadcrumbs Module Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Pages
 */
class PagesModuleBreadcrumbsHtml extends Pages\ModuleAbstract
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $list       = $this->getObject('com:pages.pathway');
        $params     = $context->data->module->getParameters();
        $translator = $this->getObject('translator');

        if($params->get('homeText'))
        {
            $item = new \stdClass();
            $item->name = $params->get('homeText', $translator('Home'));

            $home = $this->getObject('pages')->getPrimary();
            $item->link = $this->getRoute($home->getLink()->getQuery().'&Itemid='.$home->id);

            array_unshift($list, $item);
        }

        $context->data->list = $list;

        parent::_fetchData($context);
    }
}