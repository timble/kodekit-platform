<?php
/**
 * @version 	$Id$
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Abstract Database Behavior
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
abstract class KDatabaseBehaviorAbstract extends KMixinAbstract implements KDatabaseBehaviorInterface
{
	/**
	 * The behavior priority
	 *
	 * @var integer
	 */
	protected $_priority;

	/**
     * The service identifier
     *
     * @var KServiceIdentifier
     */
    private $__service_identifier;

    /**
     * The service container
     *
     * @var KServiceInterface
     */
    private $__service_container;

	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null)
	{
	    //Set the service container
        if(isset($config->service_container)) {
            $this->__service_container = $config->service_container;
        }

        //Set the service identifier
        if(isset($config->service_identifier)) {
            $this->__service_identifier = $config->service_identifier;
        }

		parent::__construct($config);

		$this->_priority = $config->priority;

	    //Automatically mixin the behavior with the mixer (table object)
		if($config->auto_mixin) {
		    $this->mixin($this);
		}
	}

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
			'priority'   => KCommand::PRIORITY_NORMAL,
    	    'auto_mixin' => false
	  	));

    	parent::_initialize($config);
   	}

	/**
	 * Get the priority of a behavior
	 *
	 * @return	integer The command priority
	 */
  	public function getPriority()
  	{
  		return $this->_priority;
  	}

	/**
	 * Command handler
	 *
	 * This function transmlated the command name to a command handler function of
	 * the format '_beforeX[Command]' or '_afterX[Command]. Command handler
	 * functions should be declared protected.
	 *
	 * @param 	string  	The command name
	 * @param 	object   	The command context
	 * @return 	boolean		Can return both true or false.
	 */
	final public function execute( $name, KCommandContext $context)
	{
		$identifier = clone $context->caller->getIdentifier();
		$type       = array_pop($identifier->path);

		$parts  = explode('.', $name);
		$method = '_'.$parts[0].ucfirst($type).ucfirst($parts[1]);

		if(method_exists($this, $method))
		{
			if($context->data instanceof KDatabaseRowInterface) {
			     $this->setMixer($context->data);
			}

			return $this->$method($context);
		}

		return true;
	}

	/**
     * Saves the row or rowset in the database.
     *
     * This function specialises the KDatabaseRow or KDatabaseRowset save
     * function and auto-disables the tables command chain to prevent recursive
     * looping.
     *
     * @return KDatabaseRowAbstract or KDatabaseRowsetAbstract
     * @see KDatabaseRow::save or KDatabaseRowset::save
     */
    public function save()
    {
        $this->getTable()->getCommandChain()->disable();
        $this->_mixer->save();
        $this->getTable()->getCommandChain()->enable();

        return $this->_mixer;
    }

    /**
     * Deletes the row form the database.
     *
     * This function specialises the KDatabaseRow or KDatabaseRowset delete
     * function and auto-disables the tables command chain to prevent recursive
     * looping.
     *
     * @return KDatabaseRowAbstract
     */
    public function delete()
    {
        $this->getTable()->getCommandChain()->disable();
        $this->_mixer->delete();
        $this->getTable()->getCommandChain()->enable();

        return $this->_mixer;
    }

    /**
     * Get an object handle
     *
     * This function only returns a valid handle if one or more command handler
     * functions are defined. A commend handler function needs to follow the
     * following format : '_afterX[Event]' or '_beforeX[Event]' to be
     * recognised.
     *
     * @return string A string that is unique, or NULL
     * @see execute()
     */
    public function getHandle()
    {
        $methods = $this->getMethods();

        foreach($methods as $method)
        {
            if(substr($method, 0, 7) == '_before' || substr($method, 0, 6) == '_after') {
                return parent::getHandle();
            }
        }

        return null;
    }

    /**
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a function of format is[Behavior]
     * to allow client code to check if the behavior is callable.
     *
     * @param object The mixer requesting the mixable methods.
     * @return array An array of methods
     */
    public function getMixableMethods(KObject $mixer = null)
    {
        $methods   = parent::getMixableMethods($mixer);
        $methods[] = 'is'.ucfirst($this->getIdentifier()->name);

        return array_diff($methods, array('execute', 'save', 'delete', 'getHandle', 'getPriority', 'getIdentifier', 'getService'));
    }

	/**
	 * Get an instance of a class based on a class identifier only creating it
	 * if it doesn't exist yet.
	 *
	 * @param	string|object	The class identifier or identifier object
	 * @param	array  			An optional associative array of configuration settings.
	 * @throws	KServiceServiceException
	 * @return	object  		Return object on success, throws exception on failure
	 * @see 	KObjectServiceable
	 */
	final public function getService($identifier, array $config = array())
	{
	    return $this->__service_container->get($identifier, $config);
	}

	/**
	 * Gets the service identifier.
	 *
	 * @return	KServiceIdentifier
	 * @see 	KObjectServiceable
	 */
	final public function getIdentifier($identifier = null)
	{
		if(isset($identifier)) {
		    $result = $this->__service_container->getIdentifier($identifier);
		} else {
		    $result = $this->__service_identifier;
		}

	    return $result;
	}
}