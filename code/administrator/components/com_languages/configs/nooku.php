<?php
/**
 * @version		$Id: nooku.php 1121 2010-05-26 16:53:49Z johan $
 * @category    Nooku
 * @package     Nooku_Administrator
 * @subpackage  Config
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * NOTE: This is an manually editable configuration file. There is no UI for 
 * this, because they're not meant for typical users. Edit the public properties
 * in the class below.
 */

/**
 * Nooku Configuration
 * 
 * Only meant for manual editing some lesser used options such as the demo site functionality
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category    Nooku
 * @package     Administrator
 * @subpackage  Config
 * @version		1.0
 */
class NookuConfigNooku extends KObject
{	
	/**
	 * Allow managers and administrators to have the same management permissions as Super Admins
	 *
	 * @var bool
	 */
	public $managersCanManage = false;
}
