<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class ViewHtml extends Library\ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_fetch' => false,
        ));

        parent::_initialize($config);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        //do nothing
    }
}