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
 * Enable button class for a toolbar
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @subpackage  Button
 */
class ComDefaultToolbarButtonEnable extends ComDefaultToolbarButtonDefault
{
	/**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'icon' => 'icon-32-publish'
        ));
        
        parent::_initialize($config);
    }
    
    public function getOnClick()
    {
        $url  = KRequest::url();
        $json = "{method:'post', url:'$url&'+id, params:{action:'edit', enabled:1, '$this->_token_name':'$this->_token_value'}}";

        $msg    = JText::_('Please select an item from the list');
        return 'var id = Koowa.Grid.getIdQuery();'
            .'if(id){new Koowa.Form('.$json.').submit();} '
            .'else { alert(\''.$msg.'\'); return false; }';
    }
}