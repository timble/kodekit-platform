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
 * Articles Html View Class
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
        $this->getToolbar()
            ->append('divider')
            ->append('publish')
            ->append('unpublish')
            ->append('divider')
            ->append('archive')
            ->append('unarchive')
            ->append('divider')
            ->append('preferences');

       	$folders = KFactory::get('admin::com.articles.model.folders')->getList();
       	$this->assign('folders', $folders);

        return parent::display();
    }

    public function getToolbar()
    {
        $name = $this->getName();

        $identifier       = clone $this->_identifier;
        $identifier->path = array('toolbar');

        if($this->state->trashed) {
            $identifier = 'admin::com.versions.toolbar.trash';
        } else {
            $identifier->name = $name;
        }

        return KFactory::get($identifier);
    }
}