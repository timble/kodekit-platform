<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.view');

/**
 * Render a contact as a vcard
 * 
 * @author		Johan Janssens <johan@nooku.org>
 * @package		Joomla.Framework
 * @subpackage	Contacts
 * @since		Nooku Server 0.7
 */
class ContactViewContact extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$user		= JFactory::getUser();
		$document	= JFactory::getDocument();
		$model		= $this->getModel();

		// Get the parameters of the active menu item
		$menus	=  JSite::getMenu();
		$menu    = $menus->getActive();

		$pparams = $mainframe->getParams('com_contact');

		// Push a model into the view
		$model		= &$this->getModel();

		// Selected Request vars
		// ID may come from the contact switcher
		if (!($contactId	= JRequest::getInt( 'contact_id',	0 ))) {
			$contactId	= JRequest::getInt( 'id',			$contactId );
		}

		// query options
		$options['id']	= $contactId;
		$options['aid']	= $user->get('aid', 0);

		$contact	= $model->getContact( $options );

		// check if we have a contact
		if (!is_object( $contact )) {
			JError::raiseError( 404, 'Contact not found' );
			return;
		}
		
		// check if access is registered/special
		if (($contact->access > $user->get('aid', 0)) || ($contact->category_access > $user->get('aid', 0))) 
		{
			$uri		= JFactory::getURI();
			$return		= $uri->toString();
			
			$url  = 'index.php?option=com_user&view=login';
			$url .= '&return='.base64_encode($return);
			
			$mainframe->redirect($url, JText::_('You must login first') );
			
		}

		// Set the document page title
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object( $menu ) && isset($menu->query['view']) && $menu->query['view'] == 'contact' && isset($menu->query['id']) && $menu->query['id'] == $contact->id) 
		{
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$pparams->set('page_title',	$contact->name);
			}
		} 
		else $pparams->set('page_title',	$contact->name);
		
		$document->setTitle( $pparams->get( 'page_title' ) );

		// Adds parameter handling
		$contact->params = new JParameter($contact->params);
		
		$pparams->merge($contact->params);

		// Show the Vcard if contact parameter indicates (prevents direct access)
		if (($contact->params->get('allow_vcard', 0)) && ($user->get('aid', 0) >= $contact->access))
		{
			// Parse the contact name field and build the nam information for the vcard.
			$firstname 	= null;
			$middlename = null;
			$surname 	= null;

			// How many parts do we have?
			$parts = explode(' ', $contact->name);
			$count = count($parts);

			switch ($count) 
			{
				case 1 :
					// only a first name
					$firstname = $parts[0];
					break;

				case 2 :
					// first and last name
					$firstname = $parts[0];
					$surname = $parts[1];
					break;

				default :
					// we have full name info
					$firstname = $parts[0];
					$surname = $parts[$count -1];
					for ($i = 1; $i < $count -1; $i ++) {
						$middlename .= $parts[$i].' ';
					}
					break;
			}
			
			// quick cleanup for the middlename value
			$middlename = trim($middlename);
			
			$filename = str_replace(' ', '_', $contact->name);
			
			// Create a new vcard object and populate the fields
			$document
				->setPhoneNumber($contact->telephone, 'PREF;WORK;VOICE')
				->setPhoneNumber($contact->fax, 'WORK;FAX')
				->setName($surname, $firstname, $middlename, '')
				->setAddress('', '', $contact->address, $contact->suburb, $contact->state, $contact->postcode, $contact->country, 'WORK;POSTAL')
				->setEmail($contact->email_to)
				->setNote($contact->misc)
				->setURL( JURI::base(), 'WORK')
				->setTitle($contact->con_position)
				->setFilename($filename);
		} 
		else 
		{
			JError::raiseWarning('SOME_ERROR_CODE', 'ContactController::vCard: '.JText::_('ALERTNOTAUTH'));
			return false;
		}
	}
}