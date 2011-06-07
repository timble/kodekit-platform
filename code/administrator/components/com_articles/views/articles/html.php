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
 * Component Loader
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

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