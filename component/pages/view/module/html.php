<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Html Module View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class ViewModuleHtml extends Library\ViewHtml
{
    /**
     * Fetch the view data
     *
     * Bind the params in the state to the module
     *
     * @param Library\ViewContext	$context A view context object
     * @return void
     */
    protected function _fetchData(Library\ViewContext $context)
    {
        parent::_fetchData($context);

        $params = $this->getModel()->getState()->get('params');
        $context->data->module->getParameters()->setData($params);
    }
}