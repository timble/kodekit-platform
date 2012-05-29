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
 * Section element class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class JElementSection extends JElement
{

    var $_name = 'section';

    function fetchElement($name, $value, &$node, $control_name) {
        $db = &JFactory::getDBO();

        $query = 'SELECT id, CONCAT(CONCAT_WS(\' ( id=\', title, id), \' )\') AS title FROM #__sections ORDER BY title';

        $db->setQuery($query);
        $options = $db->loadObjectList();

        return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"',
            'id', 'title', $value, $control_name . $name);
    }
}

?>