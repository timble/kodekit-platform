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
 * Select Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Kodekit\Platform\Users
 */
class TemplateHelperSelect extends Library\TemplateHelperSelect
{
    public function roles($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'  => 'role_id',
        ));

        $config->options = $this->options(array(
            'entity' => $this->getObject('com:users.model.roles')->sort('id')->fetch(),
            'label'   => 'title'
        ));

        return $this->radiolist($config);
    }
}