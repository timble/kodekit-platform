<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

/**
 * Category Element
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Categories
 */
class JElementCategory extends JElement
{
    var $_name = 'category';

    public function fetchElement($name, $value, $param, $group)
    {
        $config = array(
            'name'     => $group . '[' . $name . ']',
            'selected' => $value,
            'filter'   => array('table'    => (string) $param->attributes()->table),
            'attribs'  => array('class' => 'inputbox'),
        );

        $template = Kodekit::getObject('com:pages.view.page')->getTemplate();
        $html     = Kodekit::getObject('com:categories.template.helper.listbox',  array('template' => $template))->categories($config);
        return $html;
    }
}