<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Frontpage Articles Html View Class
 *
 * @author      Richie Mortimer <http://nooku.assembla.com/profile/ravenlife>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 */
class ComFrontpageViewArticlesHtml extends ComFrontpageViewHtml
{
    public function display()
    {
        $this->getToolbar()
            ->reset()
            ->setTitle('Frontpage Manager')
            ->append('archive')
            ->append('divider')
            ->append('publish')
            ->append('unpublish')
            ->append('divider')
            ->append(KFactory::tmp('admin::com.frontpage.toolbar.button.delete', array('text' => 'Remove')));

        return parent::display();
    }
}