<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Search Pages Element
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Component\Articles
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

        $template = Library\ObjectManager::getInstance()->getObject('com:pages.view.page')->getTemplate();
        $html = Library\ObjectManager::getInstance()->getObject('com:articles.template.helper.listbox', array('template' => $template))->searchpages($config);
        return $html;
    }
}