<?php
class ComArticlesViewArticlesHtml extends ComArticlesViewHtml
{
    public function display()
    {
        KFactory::get('admin::com.articles.toolbar.articles')
            ->append('divider')
            ->append('publish')
            ->append('unpublish')
            ->append('divider')
            ->append('archive')
            ->append('unarchive')
            ->append('divider')
            ->append('preferences');

        return parent::display();
    }
}