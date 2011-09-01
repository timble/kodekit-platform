<?php
/**
 * @version     $Id: mod_banners.php 2176 2011-07-12 14:08:26Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Feed
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */

echo KFactory::get('mod://admin/feed.html')
    	->module($module)
    	->attribs($attribs)
    	->display();
