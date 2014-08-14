<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Menu Module Html View
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Pages
 */
class PagesModuleMenuHtml extends PagesModuleDefaultHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $params = $this->module->getParameters();

        $start    = $params->get('start_level');
        $end      = $params->get('end_level');
        $children = $params->get('show_children', 'active');
        $pages    = $this->getObject('application.pages');
        $groups   = $this->getObject('user')->getGroups();

        // Make sure that pages without an assigned group are also included.
        $groups[] = 0;

        $context->data->active = $pages->getActive();
        $context->data->pages  = $pages->find(array('pages_menu_id' => $params->get('menu_id'), 'hidden' => 0, 'users_group_id' => $groups));

        foreach(clone $context->data->pages as $page)
        {
            $extract = false;
            
            // Extract if level is lower than start.
            if($page->level < $start) {
                $extract = true;
            }
            
            // Extract if level is higher than end.
            if(!$extract && $end > $start && $page->level > $end) {
                $extract = true;
            }
            
            // Extract if path is not in the active branch.
            if(!$extract && $children == 'active' && $page->level > 1)
            {
                if(implode('/', $page->getParentIds()) != implode('/', array_slice(explode('/', $context->data->active->path), 0, count($page->getParentIds())))) {
                    $extract = true;
                }
            }
            
            if($extract) {
                $context->data->pages->remove($page);
            }
        }

        parent::_fetchData($context);
    }
}