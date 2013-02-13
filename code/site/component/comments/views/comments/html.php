<?php
class ComCommentsViewCommentsHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $this->user = JFactory::getUser();
        return parent::display();
    }
}