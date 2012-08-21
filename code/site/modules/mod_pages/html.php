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
 * @subpackage  Search
 */
class ModPagesHtml extends ModDefaultHtml
{
    public function display()
    {
        $start    = $this->params->get('startLevel');
        $end      = $this->params->get('endLevel');
        $children = $this->params->get('showAllChildren');
        $pages    = $this->getService('application')->getPages();

        $this->active = $pages->getActive();
        $this->pages  = $pages->find(array('pages_menu_id' => $this->params->get('menu_id'), 'hidden' => 0));

        foreach($pages as $page)
        {
            if($page->level - 1 < $start || ($page->level - 1 != $start && (!$children || ($end != 0 && ($end <= $start || $page->level - 1 > $end))))) {
                $this->pages->extract($page);
            }
        }
        
        return parent::display();
    }
}