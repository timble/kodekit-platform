<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Pages Module Html Class
 *
 * @author    	Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesModuleMenuHtml extends ComDefaultModuleDefaultHtml
{
    public function display()
    {
        $start    = $this->module->params->get('start_level');
        $end      = $this->module->params->get('end_level');
        $children = $this->module->params->get('show_children', 'active');
        $pages    = $this->getService('application.pages');
        $groups   = JFactory::getUser()->getGroups();

        // Make sure that pages without an assigned group are also included.
        $groups[] = 0;

        $this->active = $pages->getActive();
        $this->pages  = $pages->find(array('pages_menu_id' => $this->module->params->get('menu_id'), 'hidden' => 0, 'users_group_id' => $groups));

        foreach(clone $this->pages as $page)
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
                if(!is_null($this->active) && (implode('/', $page->getParentIds()) != implode('/', array_slice(explode('/', $this->active->path), 0, count($page->parent_ids))))) {
                    $extract = true;
                }
            }
            
            if($extract) {
                $this->pages->extract($page);
            }
        }
        
        $this->show_title = $this->module->params->get('show_title', false);
        $this->class      = $this->module->params->get('class', 'nav');
        
        return parent::display();
    }
}