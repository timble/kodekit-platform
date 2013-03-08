<?php
/**
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Listbox Template Helper Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersTemplateHelperListbox extends ComBaseTemplateHelperListbox
{
    public function groups( $config = array())
    {
    	$config = new Framework\Config($config);
    	$config->append(array(
    		'model' 	=> 'groups',
    		'value'		=> 'id',
    		'text'		=> 'name'
    	));
    
    	return parent::_listbox($config);
    }
    
    public function users($config = array())
    {
        $config = new Framework\Config($config);
		$config->append(array(
			'model'		=> 'users',
			'name'		=> 'id',
		    'filter'	=> array(
		    	'group'      => 18,
		    	'group_tree' => true
		    )
		));
		
		//@TODO : Fix - Forcing config option because of name collisions
		$config->text = 'name';
		$config->sort = 'name';
		
		return parent::_render($config);
    }

    public function languages($config = array())
    {
        $config = new Framework\Config($config);

        $config->append(array(
            'value'      => 'iso_code',
            'text'       => 'name',
            'identifier' => 'com://admin/languages.model.languages',
            'filter'     => array('application' => 'site', 'enabled' => 1)));

        $listbox = parent::_listbox($config);

        if (!$config->size) {
            $listbox = str_replace('size="1"', '', $listbox);
        }

        return $listbox;
    }
}