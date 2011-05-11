<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Toolbar Button
 *
 * @author      Ercan …zkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages   
 */

class ComLanguagesToolbarButtonSet extends KToolbarButtonPost
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
			'text' => JText::_('Default'),
        ));

        parent::_initialize($config);
    }
	
    public function getOnClick()
    {
		$url  = KRequest::url();
        $json = "{method:'post', url:'$url&'+id,params:{action:'edit', default:1, '$this->_token_name':'$this->_token_value'}}";
        
        $msg    = JText::_('Please select an item from the list');
        return 'var id = Koowa.Grid.getIdQuery();'
            .'if(id){new Koowa.Form('.$json.').submit();} '
            .'else { alert(\''.$msg.'\'); return false; }';
    } 
}