<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * View helper for creating different select lists
 */
class ProfilesHelperSelect extends KObject
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

		return KTemplate::loadHelper('select.genericlist', $options, 'gender', 'class="inputbox" size="1" style="width:142px"', 'value', 'text', $gender );
	}

	public function offices($selected, $name = 'profiles_office_id', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = true)
 	{
		$items = KFactory::get('admin::com.profiles.model.offices')->getAll();

		// Add first option to list
        $list = array();
		if($allowAny) {
			$list[] =  KTemplate::loadHelper('select.option', '', '- '.JText::_( 'Select Office' ).' -', 'profiles_office_id', 'title' );
		}

		// Marge first option with departments
		$list = array_merge( $list, $items->getData());

		$attribs['style'] = "width:142px";

		// build the HTML list
		return KTemplate::loadHelper('select.genericlist', $list, $name, $attribs, 'profiles_office_id', 'title', $selected, $idtag );
 	}

	public function departments($selected, $name = 'profiles_department_id', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = true)
 	{
		$items = KFactory::get('admin::com.profiles.model.departments')->getAll();

		// Add first option to list
        $list = array();
		if($allowAny) {
			$list[] = KTemplate::loadHelper('select.option', '', '- '.JText::_( 'Select Department' ).' -', 'profiles_department_id', 'title' );
		}

		// Marge first option with departments
		$list = array_merge( $list, $items->getData());

		$attribs['style'] = "width:142px";

		// build the HTML list
		return KTemplate::loadHelper('select.genericlist', $list, $name, $attribs, 'profiles_department_id', 'title', $selected, $idtag );
 	}

    public function users($selected, $name, $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false)
    {

       $items = KFactory::get('admin::com.profiles.profiles.users')->getUsers($selected);

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