<?php
/**
* @version		$Id: toolbar.sections.html.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @subpackage	Sections
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @package		Joomla
* @subpackage	Sections
*/
class TOOLBAR_sections {
	/**
	* Draws the menu for Editing an existing category
	*/
	function _EDIT( $edit) {
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		JArrayHelper::toInteger($cid, array(0));

		$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );

		JToolBarHelper::title( $text.' '.JText::_( 'Section' ), 'sections.png' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ( $edit ) {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		} else {
			JToolBarHelper::cancel();
		}
	}
	/**
	* Draws the menu for Copying existing sections
	* @param int The published state (to display the inverse button)
	*/
	function _COPY() {
		JToolBarHelper::title( $edit.' '.JText::_( 'Section' ), 'section.png' );
		//JToolBarHelper::title( JText::_( 'Copy Section' ), 'sections.png' );
		JToolBarHelper::save( 'copysave' );
		JToolBarHelper::cancel();
	}
	/**
	* Draws the menu for Editing an existing category
	*/
	function _DEFAULT(){
		JToolBarHelper::title( JText::_( 'Section Manager' ), 'sections.png' );
		JToolBarHelper::addNewX();
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList();
		JToolBarHelper::spacer();
		JToolBarHelper::customX( 'copyselect', 'copy.png', 'copy_f2.png', 'Copy', true );
		JToolBarHelper::spacer();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();		
	}
}