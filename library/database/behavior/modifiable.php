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
     * @param 	object 	An optional ObjectConfig object with configuration options
     * @return void
     */
	protected function _initialize(ObjectConfig $config)
    {
    	$config->append(array(
			'priority'   => Command::PRIORITY_LOW,
	  	));

    	parent::_initialize($config);
   	}

	/**
	 * Get the methods that are available for mixin based
	 *
	 * This function conditionaly mixes the behavior. Only if the mixer
	 * has a 'modified_by' or 'modified_by' property the behavior will
	 * be mixed in.
	 *
	 * @param ObjectMixable $mixer The mixer requesting the mixable methods.
	 * @return array An array of methods
	 */
	public function getMixableMethods(ObjectMixable $mixer = null)
	{
		$methods = array();

		if($mixer instanceof DatabaseRowInterface && ($mixer->has('modified_by') || $mixer->has('modified_on'))) {
			$methods = parent::getMixableMethods($mixer);
		}

		return $methods;
	}

	/**
	 * Set modified information
	 *
	 * Requires a 'modified_on' and 'modified_by' column
	 *
	 * @return void
	 */
	protected function _beforeTableUpdate(CommandContext $context)
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