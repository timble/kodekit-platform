<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Article Element Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class JElementArticle extends JElement
{
    var $_name = 'Article';

    public function fetchElement($name, $value, &$node, $control_name)
    {
        $config = array(
            'name'     => $control_name . '[' . $name . ']',
            'selected' => $value,
            'table'    => $node->attributes('table'),
            'attribs'  => array('class' => array('inputbox', 'chzn-select')),
            'autocomplete' => true,
        );

        $html = KService::get('com://admin/articles.template.helper.listbox')->articles($config);
        return $html;
    }
}