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
abstract class ProfilesControllerDefault extends KoowaControllerForm
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
		$this->registerFilterBefore('save'   , 'filterInput')
			 ->registerFilterBefore('apply'  , 'filterInput');
		
		//Register created by filter
		$this->registerFilterBefore('add'    , 'filterCreated');
		
		//Register redirect messages
		$this->registerFilterAfter('save',		'filterSetMessage')
			 ->registerFilterAfter('delete',	'filterSetMessage');
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
	
    /**
	 * Filter that creates a redirect message based on the 
	 * controller
	 *
	 * @return void
	 */
	public function filterSetMessage(ArrayObject $args)
	{
		$count  = count((array) KRequest::get('post.id', 'int', 1));
		$action = $args['action'];
		$name	= $this->getIdentifier()->name;
			
		if($count > 1) {
			$this->_message = JText::sprintf('%s ' . ucfirst(KInflector::pluralize($name)) . ' ' . $action.'d', $count);
		} else {
			$this->_message = JText::_(ucfirst(KInflector::singularize($name)) . ' ' . $action.'d');
		}
	}
}