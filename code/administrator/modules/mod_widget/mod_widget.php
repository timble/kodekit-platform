<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Modules
 * @subpackage  Widget
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Description
 *   
 * @author   	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Widget
 */

echo KFactory::get('mod://admin/widget.html')
    	->module($module)
    	->attribs($attribs)
    	->display();