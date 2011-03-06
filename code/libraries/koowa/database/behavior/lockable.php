<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Database Lockable Behavior
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorLockable extends KDatabaseBehaviorAbstract
{
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return void
     */
	protected function _initialize(KConfig $config)
    {
    	$config->append(array(
			'priority'   => KCommand::PRIORITY_HIGH,
	  	));

    	parent::_initialize($config);
   	}
	
	/**
	 * Get the methods that are available for mixin based
	 *
	 * This function conditionaly mixies the behavior. Only if the mixer
	 * has a 'locked_by' property the behavior will be mixed in.
	 *
	 * @param object The mixer requesting the mixable methods.
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods = array();

		if(isset($mixer->locked_by)) {
			$methods = parent::getMixableMethods($mixer);
		}

		return $methods;
	}

	/**
	 * Lock a row
	 *
	 * Requires an 'locked_on' and 'locked_by' column
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function lock()
	{
		//Prevent lock take over, only an saved and unlocked row and be locked
		if(!$this->isNew() && !$this->locked())
		{
			$this->locked_by = (int) KFactory::get('lib.koowa.user')->get('id');
			$this->locked_on = gmdate('Y-m-d H:i:s');
			$this->save();
		}

		return true;
	}

	/**
	 * Unlock a row
	 *
	 * Requires an locked_on and locked_by column to be present in the table
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function unlock()
	{
		$userid = KFactory::get('lib.koowa.user')->get('id');

		//Only an saved row can be unlocked by the user who locked it
		if(!$this->isNew() && $this->locked_by != 0 && $this->locked_by == $userid)
		{
			$this->locked_by = 0;
			$this->locked_on = 0;

			$this->save();
		}

		return true;
	}

	/**
	 * Checks if a row is locked
	 *
	 * @return boolean	If the row is locked TRUE, otherwise FALSE
	 */
	public function locked()
	{
		$result = false;
		if(!$this->isNew())
		{
			$userid = KFactory::get('lib.koowa.user')->get('id');

			if(isset($this->locked_by) && $this->locked_by != 0 && $this->locked_by != $userid) {
				$result = true;
			}
		}
		
		return $result;
	}

	/**
	 * Get the locked information
	 *
	 * @return string	The locked information as an internationalised string
	 */
	public function lockMessage()
	{
		$message = '';

		if($this->locked())
		{
			$user = KFactory::tmp('lib.koowa.user', array($this->locked_by));
			$date = KTemplateHelper::factory('date')->format(array('date' => $this->locked_on, 'format' => '%A, %d %B %Y'));
			$time = KTemplateHelper::factory('date')->format(array('data' => $this->locked_on, 'format' => '%H:%M'));

			$message = JText::sprintf('Locked by %s on %s at %s', $user->get('name'), $date, $time);
		}

		return $message;
	}

	/**
	 * Checks if a row can be updated
	 *
	 * This function determines if a row can be updated based on it's locked_by information.
	 * If a row is locked, and not by the logged in user, the function will return false,
	 * otherwise it will return true
	 *
	 * @return boolean True if row can be updated, false otherwise
	 */
	protected function _beforeTableUpdate(KCommandContext $context)
	{
		$userid = KFactory::get('lib.koowa.user')->get('id');

		if(isset($this->locked_by) && $this->locked_by != 0)
		{
			if($this->locked_by != $userid) {
				return false;
			}
		}

		return true;
	}
}