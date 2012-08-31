<?php
class ComArticlesViewArticlesHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $components   = $this->getService('application')->getComponents();
        $translatable = $components->find(array('name' => 'com_articles'))->top()->isTranslatable();
        $this->assign('translatable', $translatable);
        
        return parent::display();
    }
}