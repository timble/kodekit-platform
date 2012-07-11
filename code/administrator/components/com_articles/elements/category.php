<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Category element class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class JElementCategory extends JElement
{

    var $_name = 'category';

    function fetchElement($name, $value, &$node, $control_name) {
        $db = &JFactory::getDBO();

        $query = 'SELECT categories.id, CONCAT_WS(\' - \', sections.title, categories.title) AS title FROM #__categories AS categories INNER JOIN #__articles_sections AS sections ON categories.section = sections.articles_section_id WHERE categories.section NOT LIKE(\'com%\') ORDER BY sections.title, categories.title';

        $db->setQuery($query);
        $options = $db->loadObjectList();

        return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"',
            'id', 'title', $value, $control_name . $name);
    }
}

?>