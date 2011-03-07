<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @subpackage	Button
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Edit button class for a toolbar
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @subpackage  Button
 */
class KToolbarButtonEdit extends KToolbarButtonAbstract
{
    public function getOnClick()
    {
        $option = KRequest::get('get.option', 'cmd');
        $view   = KInflector::singularize(KRequest::get('get.view', 'cmd'));
        $json   = "{method:'get', url:'index.php', params:{option:'$option',view:'$view',id:id}}";

        $msg    = JText::_('Please select an item from the list');
        return 'var id = Koowa.Grid.getFirstSelected();'
            .'if(id){new Koowa.Form('.$json.').submit();} '
            .'else { alert(\''.$msg.'\'); return false; }';
    }

}