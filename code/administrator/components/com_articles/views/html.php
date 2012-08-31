<?php
class ComArticlesViewHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $components   = $this->getService('application');
        $translatable = $components->find(array('name' => 'com_articles'))->top()->isTranslatable();
        $this->assign('translatable', $translatable);
        
        return parent::display();
    }
}