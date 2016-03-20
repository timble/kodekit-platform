<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Html Module View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
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