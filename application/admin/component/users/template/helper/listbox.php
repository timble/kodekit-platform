<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author   Gergo Erdosi <http://github.com/gergoerdosi>
 * @@package Component\Users
 */
class UsersTemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function groups( $config = array())
    {
    	$config = new Library\ObjectConfig($config);
    	$config->append(array(
    		'model' => 'groups',
    		'value'	=> 'id',
    		'label'	=> 'name'
    	));
    
    	return parent::_listbox($config);
    }
    
    public function users($config = array())
    {
        $config = new Library\ObjectConfig($config);
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
        $config = new Library\ObjectConfig($config);

        $config->append(array(
            'value'      => 'iso_code',
            'label'      => 'name',
            'identifier' => 'com:languages.model.languages',
            'filter'     => array('application' => 'site', 'enabled' => 1)));

        $listbox = parent::_listbox($config);

        if (!$config->size) {
            $listbox = str_replace('size="1"', '', $listbox);
        }

        return $listbox;
    }
}