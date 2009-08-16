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
		$doc 		= KFactory::get('lib.joomla.document');
		$filter		= KFactory::tmp('lib.koowa.filter.filename');
		$person 	= KFactory::get('site::com.beer.model.people')->getItem();

		$doc->setAddress($person->address);
		$doc->setEmail($person->email);
		$doc->setFilename($filter->sanitize($person->name));
		$doc->setFormattedName($person->name);
		//$doc->setNote($profile->information);
		$doc->setOrg($person->department);
		//$doc->setPhoneNumber($person->phone, 'PREF');
		$doc->setPhoneNumber($person->mobile, 'CELL');
		//$doc->setUrl($person->website, 'WORK');
		$doc->setTitle($person->title);

	}
}