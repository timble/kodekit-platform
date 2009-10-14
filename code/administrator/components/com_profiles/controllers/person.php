<?php
/**
 * @version		$Id$
 * @package		Pofiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Person Controller
 *
 * @package		Profiles
 */
class ProfilesControllerPerson extends ProfilesControllerDefault
{
	/**
	 * Filter input values, modify request
	 *
	 * @params	Arguments
	 * @return 	void
	 */
	public function filterInput(ArrayObject $args)
	{
		$alias 	= KRequest::get('post.alias', 'ascii');
		if(empty($alias)) {
			$alias = KRequest::get('post.firstname', 'ascii').'_'.KRequest::get('post.lastname', 'ascii');
		}
		
		KRequest::set('post.alias', $alias);

		// @todo proper email validation
		$filter = KFactory::tmp('lib.koowa.filter.email');
		if(!$filter->validate(KRequest::get('post.email', $filter))) 
		{
			KRequest::set('post.email', '');
			JError::raiseNotice(0, 'Not a valid email address');
		}
	}
}