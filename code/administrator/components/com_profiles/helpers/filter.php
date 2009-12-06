<?php
/**
 * @version		$Id: select.php 236 2009-10-05 16:27:02Z erdsiger $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * View helper for creating filters
 */
class ComProfilesHelperFilter extends KObject
{
	public function groups($selected, $name = 'gid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false)
    {
       $items = KFactory::get('admin::com.profiles.model.users')->getGroups();

		// Add first option to list
        $list = array();
        if($allowAny) {
            $list[] = KTemplate::loadHelper('select.option', '', JText::_( 'Select Group' ), 'gid', 'usertype' );
        }

        $list = array_merge( $list, $items );

        // build the HTML list
        return KTemplate::loadHelper('select.genericlist',  $list, $name, $attribs, 'gid', 'usertype', $selected, $idtag );
    }
}