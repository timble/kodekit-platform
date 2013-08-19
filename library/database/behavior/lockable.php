<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database Lockable Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseBehaviorLockable extends DatabaseBehaviorAbstract
{
	/**
	 * The lock lifetime
	 *
	 * @var integer
	 */
	protected $_lifetime;

	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config 	An optional ObjectConfig object with configuration options
     * @return void
     */
	protected function _initialize(ObjectConfig $config)
    {
    	$config->append(array(
			'priority'   => Command::PRIORITY_HIGH,
            'lifetime'   =>  $this->getObject('user')->getSession()->getLifetime()
	  	));

	  	$this->_lifetime = $config->lifetime;

    	parent::_initialize($config);
   	}

	/**
	 * Get the methods that are available for mixin based
	 *
	 * This function conditionally mixes the behavior. Only if the mixer has a 'locked_by' property the behavior will
     * be mixed in.
	 *
	 * @param ObjectMixable $mixer The mixer requesting the mixable methods.
	 * @return array An array of methods
	 */
	public function getMixableMethods(ObjectMixable $mixer = null)
	{
		$methods = array();

		if($mixer instanceof DatabaseRowInterface && ($mixer->has('locked_by') || $mixer->has('locked_on'))) {
			$methods = parent::getMixableMethods($mixer);
		}

		return $methods;
	}

	/**
	 * Lock a row
	 *
	 * Requires an 'locked_on' and 'locked_by' column
	 *
	 * @return boolean	If successful return TRUE, otherwise FALSE
	 */
	public function lock()
	{
		//Prevent lock take over, only an saved and unlocked row and be locked
		if(!$this->isNew() && !$this->isLocked())
		{
			$this->locked_by = (int) $this->getObject('user')->getId();
			$this->locked_on = gmdate('Y-m-d H:i:s');

            return $this->save();
		}

		return false;
	}

	/**
	 * Unlock a row
	 *
	 * Requires an locked_on and locked_by column to be present in the table
	 *
	 * @return boolean	If successful return TRUE, otherwise FALSE
	 */
	public function unlock()
	{
		$userid = $this->getObject('user')->getId();

		//Only an saved row can be unlocked by the user who locked it
		if(!$this->isNew() && $this->locked_by != 0 && $this->locked_by == $userid)
		{
			$this->locked_by = 0;
			$this->locked_on = 0;

            return $this->save();
		}

		return false;
	}

	/**
	 * Checks if a row is locked
	 *
	 * @return boolean	If the row is locked TRUE, otherwise FALSE
	 */
	public function isLocked()
	{
		$result = false;
		if(!$this->isNew())
		{
		    if(isset($this->locked_on) && isset($this->locked_by))
			{
			    $locked  = strtotime($this->locked_on);
                $current = strtotime(gmdate('Y-m-d H:i:s'));

                //Check if the lock has gone stale
                if($current - $locked < $this->_lifetime)
			    {
                    $userid = $this->getObject('user')->getId();
			        if($this->locked_by != 0 && $this->locked_by != $userid) {
			            $result= true;
                    }
			    }
			}
		}

		return $result;
	}

	/**
	 * Checks if a row can be updated
	 *
	 * This function determines if a row can be updated based on it's locked_by information. If a row is locked, and
     * not by the logged in user, the function will return false, otherwise it will return true
	 *
	 * @return boolean True if row can be updated, false otherwise
	 */
	protected function _beforeTableUpdate(CommandContext $context)
	{
		return (bool) !$this->isLocked();
	}

	/**
	 * Checks if a row can be deleted
	 *
	 * This function determines if a row can be deleted based on it's locked_by information. If a row is locked, and
     * not by the logged in user, the function will return false, otherwise it will return true
	 *
	 * @return boolean True if row can be deleted, false otherwise
	 */
	protected function _beforeTableDelete(CommandContext $context)
	{
		return (bool) !$this->isLocked();
	}
}