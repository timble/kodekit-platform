<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @subpackage	Button
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

/**
 * Toolbar Button interface
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 * @subpackage	Button
 */
interface KToolbarButtonInterface 
{
	/**
	 * Get the element name
	 * 
	 * @return	string	Button name
	 */
	public function getName();

	/**
	 * Set the parent toolbar
	 *
	 * @param 	KToolbarInterface 	Toolbar
	 */
	public function setParent(KToolbarInterface $toolbar);
	
	/**
	 * Get the parent toolbar
	 * 
	 * @return 	KToolbarInterface	Toolbar
	 */
	public function getParent();
	
	/**
	 * Render the button
	 * 
	 * @return string	Html
	 */
	public function render();
}