<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-varnish for the canonical source repository
 */

namespace Kodekit\Component\Varnish;

use Kodekit\Library;

/**
 * Html Fragment View
 *
 * @author      Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Varnish
 */
class ViewFragmentHtml extends Library\ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'template_filters'	=> array('asset', 'style', 'link', 'meta', 'script', 'title'),
        ));

        parent::_initialize($config);
    }
}
