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
 * Listbox Template Helper
 *
 * @author   Gergo Erdosi <http://github.com/gergoerdosi>
 * @@package Kodekit\Platform\Users
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function groups($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'model' => 'groups',
            'value' => 'name',
            'label' => 'name'
        ));

        return parent::_listbox($config);
    }

    public function users($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'model'     => 'users',
            'name'      => 'id',
            'filter'    => array(
                'group'  => 18,
            )
        ));

        return parent::_render($config);
    }

    public function languages($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $config->append(array(
            'value'      => 'iso_code',
            'label'      => 'name',
            'identifier' => 'com:languages.model.languages',
            'filter'     => array('application' => 'site', 'enabled' => 1)));

        $listbox = parent::_listbox($config);

        if (!$config->size) {
            $listbox = str_replace('size="1"', '', $listbox);
        }

        return $listbox;
    }
}