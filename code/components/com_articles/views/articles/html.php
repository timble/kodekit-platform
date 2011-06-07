<?php
/**
 * @version     $Id: articles.php 1633 2011-06-07 19:24:17Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articles Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesViewArticlesHtml extends ComDefaultViewHtml
{
    public function display()
    {
        require_once JPATH_COMPONENT.'/helpers/route.php';

        $parameters = $this->getModel()->getParameters($this->getLayout());
        $this->assign('parameters', $parameters);

        $this->assign('user', KFactory::get('lib.joomla.user'));

        if(in_array($this->getLayout(), array('category_blog', 'category_default')))
        {
            $category = KFactory::tmp('admin::com.categories.model.categories')
                ->set('id', $this->getModel()->getState()->category)
                ->getItem();

            $this->assign('category', $category);
        }

        if(in_array($this->getLayout(), array('section_blog', 'section_default')))
        {
            $section = KFactory::tmp('admin::com.sections.model.sections')
                ->set('id', $this->getModel()->getState()->section)
                ->getItem();

            $this->assign('category', $category);
        }

        if($this->getLayout() == 'section_default')
        {
            $categories = KFactory::tmp('admin::com.categories.model.categories')
                ->set('section', $this->getModel()->getState()->section)
                ->getList();

            $this->assign('categories', $categories);
        }

        if(in_array($this->getLayout(), array('featured', 'category_blog', 'section_blog')))
        {
            $offset = ($this->getModel()->getState()->offset + $parameters->get('num_leading_articles')
                + $parameters->get('num_intro_articles'));

            $model = clone $this->getModel();
            $links = $model
                ->set('limit', $parameters->get('num_links'))
                ->set('offset', $offset)
                ->getList();

            $this->assign('links', $links);
        }

        return parent::display();
    }
}