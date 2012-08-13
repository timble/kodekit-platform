<?php
class ModPagesHtml extends ModDefaultHtml
{
    public function display()
    {
        $start    = $this->params->get('startLevel');
        $end      = $this->params->get('endLevel');
        $children = $this->params->get('showAllChildren');
        $pages    = JFactory::getApplication()->getPages();
        
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