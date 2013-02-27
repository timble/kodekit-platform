<?php
/**
* @package     	Nooku_Server
* @subpackage  	Contacts
* @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
* @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link			http://www.nooku.org
*/

/**
 * Contact Vcard View
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsViewContactVcard extends KViewVcard
{
    public function display()
    {
        $contact = $this->getModel()->getRow();
       
        if(!empty($contact->email_to)) {
            $this->setEmail($contact->email_to);
        }

        if(!empty($contact->name)) {
            $this->setFormattedName($contact->name);
        } 

        if(!empty($contact->con_position)) {
            $this->setTitle($contact->con_position);
        }
        
        if(!empty($contact->address) || !empty($contact->suburb) || !empty($contact->state) || !empty($contact->postcode) || !empty($contact->country)) {
            $this->setAddress('', '', $contact->address, $contact->suburb, $contact->state, $contact->postcode, $contact->country, 'WORK;POSTAL;');
        }

        if(!empty($contact->mobile)) {
            $this->setPhoneNumber($contact->telephone, 'WORK;CELL;');
        }

        if(!empty($contact->telephone)) { 
            $this->setPhoneNumber($contact->telephone);
        }

        if(!empty($contact->fax)) {
           $this->setPhoneNumber($contact->fax, 'WORK;FAX;');
        }

        if(!empty($contact->misc)) {
            $this->setNote($contact->misc);
        }

        if(!empty($contact->webpage)) {
            $this->setURL($contact->webpage);
        }

        return parent::display();
    }
}