<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesViewPersonVcard extends KViewVcard
{
	public function display()
	{
		$person = KFactory::get('site::com.profiles.model.people')->getItem();

		$this->setName($person->lastname, $person->firstname, $person->middlename)
			 ->setOrg($person->department)
			 ->setTitle($person->position)
			 ->setPhoneNumber($person->phone , 'PREF;WORK;VOICE')
			 ->setPhoneNumber($person->mobile, 'WORK;VOICE;CELL')
			 ->setAddress('', $person->address1, $person->address2, $person->city, $person->state, $person->postcode, $person->country   , 'WORK;POSTAL')
			 ->setLabel('', $person->address1, $person->address2, $person->city, $person->state, $person->postcode, $person->country   , 'WORK;POSTAL')
			 ->setEmail($person->email)
			 ->setNote($person->bio)
			 ->setPhoto(file_get_contents('http://www.gravatar.com/avatar.php?gravatar_id='.strtolower($person->email)).'&size=64');
		   //->setUrl($person->website    , 'WORK')
			
		parent::display();
	}
}