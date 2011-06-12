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
 * Newsfeeds HTML View Class
 *
 * @author      Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */
class ComNewsfeedsViewHtml extends ComDefaultViewHtml
{
    protected function _initialize(KConfig $config)
    {
        // TODO: Add this to config.
        JSubMenuHelper::addEntry(JText::_('Newsfeeds'), 'index.php?option=com_newsfeeds&view=newsfeeds', true);
        JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&view=categories&section=com_newsfeeds');

        parent::_initialize($config);
    }
}
