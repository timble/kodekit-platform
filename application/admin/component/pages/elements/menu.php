<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Menu Element Class
 *
 * @author      Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class JElementMenu extends JElement
{
    var $_name = 'Menu';

    public function fetchElement($name, $value, &$node, $control_name)
    {
        $config = array(
            'name'     => $control_name . '[' . $name . ']',
            'selected' => $value,
            'deselect' => false
        );

        $html = KServiceManagar::get('com://admin/pages.template.helper.listbox')->menus($config);
        return $html;
    }
}