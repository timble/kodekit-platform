<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Newsfeeds HTML View Class - Newsfeeds
 *
 * @author      Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */

class ComNewsfeedsViewNewsfeedsHtml extends ComNewsfeedsViewHtml
{
    public function display()
    {
        $this->getToolbar()
            ->setTitle('Newsfeed Manager', 'newsfeed')
            ->append('divider')
            ->append('enable')
            ->append('disabled');

        $this->assign('user', KFactory::get('lib.joomla.user'));

        $categories = KFactory::tmp('admin::com.categories.model.categories')
            ->set('section', 'com_newsfeeds')
            ->set('limit', 0)
            ->getList();

        $this->assign('categories', $categories);

        return parent::display();
    }
}