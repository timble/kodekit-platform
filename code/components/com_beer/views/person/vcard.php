<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * @version		$Id$
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerViewPerson extends KViewVcard
{
	public function display($tpl = null)
	{
		$person = KFactory::get('site::com.beer.model.people')->getItem();

		$this->setName($person->lastname, $person->firstname, $person->middlename)
			 ->setOrg($person->department)
			 ->setTitle($person->position)
			 ->setPhoneNumber($person->phone , 'PREF;WORK;VOICE')
			 ->setPhoneNumber($person->mobile, 'WORK;VOICE;CELL')
			 //->setAddress($person->address   , 'WORK;POSTAL')
			 //->setLabel($person->address     , 'WORK;POSTAL')
			 ->setEmail($person->email)
			 //->setUrl($person->website		   , 'WORK')
			 ->setNote($person->bio);
			
		parent::display();
	}
}