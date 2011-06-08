<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Article Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesViewArticleHtml extends ComArticlesViewHtml
{
    public function display()
    {
        $categories = KFactory::get('admin::com.articles.model.articles')
            ->getCategories();

        $this->assign('categories', $categories);
        $this->assign('user', KFactory::get('lib.joomla.user'));

        $folders = KFactory::get('admin::com.articles.model.folders')->getList();
        $this->assign('folders', $folders);

        return parent::display();
    }
}