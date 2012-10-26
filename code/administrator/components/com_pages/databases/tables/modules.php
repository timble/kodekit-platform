<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Modules Database Table Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseTableModules extends KDatabaseTableDefault
{
    public function  _initialize(KConfig $config) 
    {
        $config->identity_column = 'id';
		
        $config->append(array(
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable',
                'com://admin/pages.database.behavior.orderable' => array('strategy' => 'flat')
            ),
            'filters' => array(
                'content' => array('html', 'tidy'),
                'params'  => 'ini' 
                )
            ));
     
        parent::_initialize($config);
    }

	/**
	 * Get default values for all columns
	 *
	 * This method is specialized in order to set the default module position 
	 * and published state
	 * 
	 * @return  array
	 */
	public function getDefaults()
	{
		$defaults = parent::getDefaults();
		
		$defaults['position']		= 'left';
		$defaults['published']		= 1;
		$defaults['description']	= '';
	     
		return $defaults;
	}
}