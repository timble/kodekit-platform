<?php
/**
 * View helper for creating different select lists
 */
class BeerHelperSelect extends KTemplateHelperSelect
{
	public static function offices($selected, $name = 'filter_office_id', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false)
 	{
		$items = KFactory::get('admin::com.beer.model.offices')->getList();

		// Build Language list
        $list = array();
		if($allowAny) {
			$list[] =  self::option('', '- '.JText::_( 'Select Office' ).' -', 'beer_office_id', 'title' );
		}

		$list = array_merge( $list, $items->toArray());

		// build the HTML list
		return self::genericlist($list, $name, $attribs, 'beer_office_id', 'title', $selected, $idtag );
 	}
 	
	public static function departments($selected, $name = 'filter_department_id', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false)
 	{
		$items = KFactory::get('admin::com.beer.model.departments')->getList();

		// Build Language list
        $list = array();
		if($allowAny) {
			$list[] =  self::option('', '- '.JText::_( 'Select Department' ).' -', 'beer_department_id', 'title' );
		}

		$list = array_merge( $list, $items->toArray());

		// build the HTML list
		return self::genericlist($list, $name, $attribs, 'beer_department_id', 'title', $selected, $idtag );
 	}
}