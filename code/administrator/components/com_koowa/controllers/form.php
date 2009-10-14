<?php
/** 
 * @version		$Id: person.php 246 2009-10-12 22:41:50Z johan $
 * @package		Koowa
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Form Controller
 *
 * @package		Koowa
 */
class KoowaControllerForm extends KControllerForm
{
/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		//Register redirect messages
		$this->registerFilterAfter('save',		'filterSetMessage')
			 ->registerFilterAfter('delete',	'filterSetMessage');
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