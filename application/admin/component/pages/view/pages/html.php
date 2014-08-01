<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Pages Html View
 *   
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Pages
 */
class PagesViewPagesHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $basepaths = $this->getObject('manager')->getClassLoader()->getBasepaths();

        $context->data->applications = array_keys($basepaths);
        $context->data->menus        = $this->getObject('com:pages.model.menus')->fetch();

        parent::_fetchData($context);
    }
}