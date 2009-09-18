<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * View helper for creating different select lists
 */
class BeerHelperSelect extends KObject
{
	public function enabled( $enabled=0)
	{
	// @todo select.genericlist doesn't know the difference between 0 and '' and null, see Nooku Framework ticket #83
	/*
		$options = array();
		$options[] = KTemplate::loadHelper('select.option',  '', '- '.JText::_( 'Select State' ) .' -');
		$options[] = KTemplate::loadHelper('select.option',  1, JText::_( 'Enabled' ));
		$options[] = KTemplate::loadHelper('select.option',  0, JText::_( 'Disabled'));

		return KTemplate::loadHelper('select.genericlist', $options, 'enabled', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $enabled );
	*/
	}

	public function gender($gender=0)
	{
		$options = array();
		$options[] = KTemplate::loadHelper('select.option',  0, '- '.JText::_( 'Select Gender' ) .' -');
		$options[] = KTemplate::loadHelper('select.option',  1, JText::_( 'Male' ));
		$options[] = KTemplate::loadHelper('select.option',  2, JText::_( 'Female'));

		return KTemplate::loadHelper('select.genericlist', $options, 'gender', 'class="inputbox" size="1"', 'value', 'text', $gender );
	}

	public function offices($selected, $name = 'beer_office_id', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = true)
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
		return KTemplate::loadHelper('select.genericlist', $list, $name, $attribs, 'beer_office_id', 'title', $selected, $idtag );
 	}

	public function departments($selected, $name = 'beer_department_id', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = true)
 	{
		$items = KFactory::get('admin::com.beer.model.departments')->getAll();

		// Add first option to list
        $list = array();
		if($allowAny) {
			$list[] = KTemplate::loadHelper('select.option', '', '- '.JText::_( 'Select Department' ).' -', 'beer_department_id', 'title' );
		}

		// Marge first option with departments
		$list = array_merge( $list, $items->toArray());

		// build the HTML list
		return KTemplate::loadHelper('select.genericlist', $list, $name, $attribs, 'beer_department_id', 'title', $selected, $idtag );
 	}

    public function users($selected, $name, $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false)
    {
       $items = KFactory::get('admin::com.beer.model.users')->getUsers();

		// Add first option to list
        $list = array();
        if($allowAny) {
            $list[] = KTemplate::loadHelper('select.option', '', JText::_( 'Select User' ), 'id', 'name' );
        }

        $list = array_merge( $list, $items );

        // build the HTML list
        return KTemplate::loadHelper('select.genericlist',  $list, $name, $attribs, 'id', 'name', $selected, $idtag );
    }	
}