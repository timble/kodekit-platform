<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Menus Html View
 *   
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Pages
 */
class PagesViewMenusHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $context->data->applications = $this->getObject('object.bootstrapper')->getApplications();

        parent::_fetchData($context);
    }
}