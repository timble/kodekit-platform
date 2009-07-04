<?php
/**
 * View helper for creating different select lists
 */
class BeerHelperSelect extends KObject
{
	public static function offices($selected, $name = 'office_id', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = true)
 	{
		$items = KFactory::get('admin::com.beer.model.offices')->getAll();

		// Add first option to list
        $list = array();
		if($allowAny) {
			$list[] =  KTemplate::loadHelper('select.option', '', '- '.JText::_( 'Select Office' ).' -', 'beer_office_id', 'title' );
		}

		// Marge first option with departments
		$list = array_merge( $list, $items->toArray());

		// build the HTML list
		return KTemplate::loadHelper('select.genericlist', $list, $name, $attribs, 'office_id', 'title', $selected, $idtag );
 	}

	public static function departments($selected, $name = 'department_id', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = true)
 	{
		$items = KFactory::get('admin::com.beer.model.departments')->getAll();

		// Add first option to list
        $list = array();
		if($allowAny) {
			$list[] = KTemplate::loadHelper('select.option', '', '- '.JText::_( 'Select Department' ).' -', 'department_id', 'title' );
		}

		// Marge first option with departments
		$list = array_merge( $list, $items->toArray());

		// build the HTML list
		return KTemplate::loadHelper('select.genericlist', $list, $name, $attribs, 'department_id', 'title', $selected, $idtag );
 	}
}