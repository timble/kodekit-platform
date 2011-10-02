<?php
/**
 * @version     $Id: mod_logged.php 2634 2011-09-01 03:05:24Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Status Module
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */

echo KService::get('mod://admin/status.html')
    	->module($module)
    	->attribs($attribs)
    	->display();
