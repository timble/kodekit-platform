<?php
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Default
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Widget Module
 *   
 * @author   	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package    	Nooku_Server
 * @subpackage 	Default
 */

echo KService::get('mod://site/widget.html')
    	->module($module)
    	->attribs($attribs)
    	->display();