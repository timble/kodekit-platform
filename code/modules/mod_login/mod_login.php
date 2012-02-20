<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Login Module
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */

echo KService::get('mod://site/login.html')
    	->module($module)
    	->attribs($attribs)
    	->display();