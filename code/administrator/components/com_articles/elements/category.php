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

        $query = 'SELECT categories.id, CONCAT_WS(\' on section \', CONCAT(CONCAT_WS(\' ( id=\', categories.title, categories.id), \' )\'), CONCAT(CONCAT_WS(\' ( id=\', sections.title, sections.id), \' )\')) AS title FROM #__categories AS categories LEFT JOIN #__sections AS sections ON sections.id = categories.section WHERE categories.section NOT LIKE(\'com%\') ORDER BY sections.title';

        $db->setQuery($query);
        $options = $db->loadObjectList();

        return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"',
            'id', 'title', $value, $control_name . $name);
    }
}

?>