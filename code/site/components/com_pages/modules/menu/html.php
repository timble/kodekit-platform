<?php
/**
 * @version		$Id: weblinks.php 1291 2011-05-16 22:13:45Z johanjanssens $
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
        $start    = $this->module->params->get('start_level') + 1;
        $end      = $this->module->params->get('end_evel') + 1;
        $children = $this->module->params->get('show_children', 'active');
        $pages    = $this->getService('application.pages');

        $this->active = $pages->getActive();
        $this->pages  = $pages->find(array('pages_menu_id' => $this->module->params->get('menu_id'), 'hidden' => 0));

        foreach($pages as $page)
        {
            $extract = false;
            
            // Extract if level is lower than start or higher than end.
            if($page->level < $start || ($end > $start && $page->level > $end)) {
                $extract = true;
            }
            
            // Extract if show_children = active and page is not child of active.
            if(!$extract && $page->level > $start && $children == 'active')
            {
                if($page->level > $this->active->level + 1) {
                    $extract = true;
                }
                
                if(!$extract) {
	    			// Create a merged array from $page->path and $this->active->path
	                $paths = array_merge(explode("/", $page->path), explode("/", $this->active->path));
		            
		            // Extract if array has no duplicated values
		            if (count($paths) === count(array_unique($paths))) {
	                    $extract = true;
	                }
	            }
            } 
            
            if($extract) {
                $this->pages->extract($page);
            }
        }
        
        $this->assign('show_title', $this->module->params->get('show_title', false));
        $this->assign('class', $this->module->params->get('class', 'nav'));
        
        return parent::display();
    }
}