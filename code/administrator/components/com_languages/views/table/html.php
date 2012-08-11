<?php

class ComLanguagesViewTableHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $this->tables = $this->getService('com://admin/languages.model.tables')->getList();
        return parent::display();
    }
}