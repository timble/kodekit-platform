<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Search Pages Element Class
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class JElementSearchpages extends JElement
{
    var $_name = 'Searchpages';

    public function fetchElement($name, $value, &$node, $control_name)
    {
        $config = array(
            'name'     => $control_name . '[' . $name . ']',
            'selected' => $value,
            'deselect' => false,

        );

        $html = KServiceManager::get('com://admin/articles.template.helper.listbox')->searchpages($config);
        return $html;
    }
}