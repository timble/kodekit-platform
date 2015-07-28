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
 * Article Element
 *
 * @author Gergo Erdosi <https://github.com/gergoerdosi>
 * @package Component\Articles
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
            'attribs'  => array('class' => 'inputbox'),
            'autocomplete' => true,
        );

        $template = Library\ObjectManager::getInstance()->getObject('com:pages.view.page')->getTemplate();
        $html     = Library\ObjectManager::getInstance()->getObject('com:articles.template.helper.listbox', array('template' => $template))->articles($config);

        return $html;
    }
}