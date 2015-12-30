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
 * Menus Composite Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
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