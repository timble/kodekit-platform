<?php
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
 * User Html View
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Platform\Users
 */
class ViewUserHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $page = $this->getObject('pages')->getActive();

        $context->data->page            = $page;
        $context->data->password_length = $this->getObject('com:users.model.entity.password')->getLength();

        parent::_fetchData($context);
    }
}