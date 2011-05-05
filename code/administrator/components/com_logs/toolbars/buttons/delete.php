<?php
/**
* @version      $Id$
* @category		Nooku
* @package		Nooku Components
* @subpackage	Logs
* @copyright    Copyright (C) 2007 - 2011 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Delete button class for a toolbar
 *
 * @author      Israel Canasa <raeldc@gmail.com>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Logs
 */
class ComLogsToolbarButtonDelete extends ComDefaultToolbarButtonDefault
{
    public function getOnClick()
    { 
        $url  = KRequest::url();
        $json = "{method:'post', url:'$url&'+id, params:{action:'delete', option: 'com_logs', '$this->_token_name':'$this->_token_value'}}";

        $msg    = JText::_('Please select an item from the list');
        return 'var id = Koowa.Grid.getIdQuery();'
            .'if(id){new Koowa.Form('.$json.').submit();} '
            .'else { alert(\''.$msg.'\'); return false; }';
    }
}