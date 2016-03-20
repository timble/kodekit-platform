s<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;

/**
 * Session Html View
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Platform\Users
 */
class ViewSessionHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $pathway   = $this->getObject('pages')->getPathway();
        $pathway[] = array('title' => $this->getObject('translator')->translate('Login'));

        return parent::_actionRender($context);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        $context->data->user       = $this->getObject('user');;
        $context->data->parameters = $this->getParameters();

        parent::_fetchData($context);
    }

    public function getParameters()
    {
        $page       = $this->getObject('pages')->getActive();
        $parameters = $page->getParams('page');

        $parameters->def('registration', true);

        return $parameters;
    }
}