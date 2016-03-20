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
 * Menus Composite Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class ModelCompositeMenus extends ModelMenus implements Library\ObjectSingleton
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'decorators'     => array('lib:model.composite.decorator'),
            'state_defaults' => array(
                'enabled'     => true,
                'application' => APPLICATION_NAME,
            )
        ));

        parent::_initialize($config);
    }
}