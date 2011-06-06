<?php
class ComArticlesViewArticleHtml extends ComArticlesViewHtml
{
    public function display()
    {
        $categories = KFactory::get('admin::com.articles.model.articles')
            ->getCategories();

        $this->assign('categories', $categories);
        $this->assign('user', KFactory::get('lib.joomla.user'));

        return parent::display();
    }
}