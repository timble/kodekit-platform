<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Listbox Template Helper Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function groups( $config = array())
    {
    	$config = new KConfig($config);
    	$config->append(array(
    		'model' 	=> 'groups',
    		'value'		=> 'id',
    		'text'		=> 'name',
    		'filter'	=> array(
    			'type'      => 'group'
    		)
    	));
    
    	return parent::_listbox($config);
    }
    
    public function roles( $config = array())
    {
    	$config = new KConfig($config);
    	$config->append(array(
    		'model' 	=> 'groups',
    		'value'		=> 'id',
    		'text'		=> 'name',
    		'filter'	=> array(
    			'type'      => 'system'
    		)
    	));
    
    	return parent::_listbox($config);
    }
    
    public function users($config = array())
    {
        $config = new KConfig($config);
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
}