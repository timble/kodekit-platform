<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Html Fragment View
 *
 * @author      Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Varnish
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
