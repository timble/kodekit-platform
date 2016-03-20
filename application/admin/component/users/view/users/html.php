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
 * Users Html View
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Platform\Users
 */
class ViewUsersHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
	{
        $context->data->groups = $this->getObject('com:users.model.groups')->fetch();
        $context->data->roles  = $this->getObject('com:users.model.roles')->fetch();

        parent::_fetchData($context);
	}
}
