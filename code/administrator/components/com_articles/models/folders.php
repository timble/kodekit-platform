<?php
class ComArticlesModelFolders extends KModelAbstract
{
    public function getList()
    {
        if(!isset($this->_list))
        {
            $folders = KFactory::tmp('admin::com.articles.database.rowset.folders');

            $sections = KFactory::tmp('admin::com.sections.model.sections')
                ->set('published', 1)
                ->set('scope', 'content')
                ->set('limit', 0)
                ->set('sort', 'ordering')
                ->getList();

            foreach($sections as $section)
            {
                $folders->insert($folders->getRow()->setData(array(
                    'id'	    => $section->id,
                    'title'     => $section->title,
                    'parent_id' => 0
                ), false));
            }

            $categories = KFactory::tmp('admin::com.categories.model.categories')
                ->set('published', 1)
                ->set('section', 'com_content')
                ->set('limit', 0)
                ->set('sort', 'ordering')
                ->getList();

            foreach($categories as $category)
            {
                $folders->insert($folders->getRow()->setData(array(
                    'id'	    => $category->id,
                    'title'	    => $category->title,
                    'parent_id' => $category->section_id
                ), false));
            }

            $this->_list = $folders;
        }

        return $this->_list;
    }
}