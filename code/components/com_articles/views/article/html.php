<?php
class ComArticlesViewArticleHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $parameters = $this->getModel()->getParameters('article');
        $this->assign('parameters', $parameters);

        return parent::display();
    }
}