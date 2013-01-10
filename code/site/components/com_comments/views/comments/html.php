<?php
class ComCommentsViewCommentsHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $this->assign('user', JFactory::getUser());
        
        return parent::display();
    }
}