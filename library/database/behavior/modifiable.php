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
 * Database Modifiable Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseBehaviorModifiable extends DatabaseBehaviorAbstract
{
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	ObjectConfig $config 	An optional ObjectConfig object with configuration options
     * @return void
     */
	protected function _initialize(ObjectConfig $config)
    {
    	$config->append(array(
			'priority'   => self::PRIORITY_LOW,
	  	));

    	parent::_initialize($config);
   	}

    /**
     * Check if the behavior is supported
     *
     * Behavior requires a 'modified_by' or 'modified_by' row property
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $mixer = $this->getMixer();
        if($mixer instanceof DatabaseRowInterface && ($mixer->has('modified_by') || $mixer->has('modified_on'))) {
            return true;
        }

        return false;
    }

	/**
	 * Set modified information
	 *
	 * Requires a 'modified_on' and 'modified_by' column
	 *
     * @param DatabaseContext	$context A database context object
	 * @return void
	 */
	protected function _beforeUpdate(DatabaseContext $context)
	{
		//Get the modified columns
		$modified = $this->getTable()->filter($this->getModified());

		if(!empty($modified))
		{
			if($this->has('modified_by')) {
				$this->modified_by = (int) $this->getObject('user')->getId();
			}

			if($this->has('modified_on')) {
				$this->modified_on = gmdate('Y-m-d H:i:s');
			}
		}
	}
}