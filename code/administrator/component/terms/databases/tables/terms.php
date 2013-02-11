<?php
/**
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Terms
 * @copyright	Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Description
 *   
 * @author   	Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Terms
 */
class ComTermsDatabaseTableTerms extends KDatabaseTableDefault
{
	protected function _initialize(KConfig $config)
    {
    	$config->behaviors = array('lockable', 'creatable', 'modifiable', 'sluggable');
		
		parent::_initialize($config);
    }
}