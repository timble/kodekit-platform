<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Controller
 *
 * @package		Profiles
 */
class comProfilesControllerDefault extends comDefaultControllerView
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		//Register input filter
		$this->registerFunctionBefore('save'   , 'filterInput')
			 ->registerFunctionBefore('apply'  , 'filterInput');
		
		//Register created by filter
		$this->registerFunctionBefore('add'    , 'filterCreated');
	}

	/**
	 * Set the created by field
	 *
	 * @param	Arguments
	 * @return 	void
	 */
	public function filterCreated(ArrayObject $args)
	{
		KRequest::set('post.created_by', KFactory::get('lib.joomla.user')->get('id'));
	}
}