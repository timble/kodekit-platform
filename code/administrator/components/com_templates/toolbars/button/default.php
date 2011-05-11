<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Default Toolbar Button class, sets a template as the default one
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesToolbarButtonDefault extends KToolbarButtonPost
{
    /**
     * Sets the template as default
     *
     * @return  string
     */
    public function getOnClick()
    {
        $url  = KRequest::url();
        $json = "{method:'post', url:'$url&'+id,params:{action:'edit', 'selections[]':0, '$this->_token_name':'$this->_token_value'}}";
        
        $msg    = JText::_('Please select an item from the list');
        return 'var id = Koowa.Grid.getIdQuery();'
            .'if(id){new Koowa.Form('.$json.').submit();} '
            .'else { alert(\''.$msg.'\'); return false; }';
    }
}