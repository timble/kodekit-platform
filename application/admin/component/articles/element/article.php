<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

/**
 * Article Element
 *
 * @author Gergo Erdosi <https://github.com/gergoerdosi>
 * @package Kodekit\Platform\Articles
 */
class JElementArticle extends JElement
{
    var $_name = 'Article';

    public function fetchElement($name, $value, $param, $group)
    {
        $config = array(
            'name'     => $group . '[' . $name . ']',
            'selected' => $value,
            'table'    => (string) $param->attributes()->table,
            'attribs'  => array('class' => 'inputbox'),
            'autocomplete' => true,
        );

        $html = Kodekit::getInstance()->getObject('com:articles.template.helper.listbox')->articles($config);

        return $html;
    }
}