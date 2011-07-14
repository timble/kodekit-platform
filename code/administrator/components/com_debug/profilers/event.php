<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Event Profiler Class
 * 
 * This class decorates the KCommandEvent object intercepting the execute method.
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 */
class ComDebugProfilerEvent extends ComDebugProfilerDefault implements KCommandInterface
{
	/**
	 * The decorated object
	 *
	 * @var object
	 */
	protected $_command;
	
	/**
	 * The database queries
	 * 
	 * @var array
	 */
    protected $_queries = array();
    
 	/**
     * Constructor.
     *
     * @param	object  An optional KConfig object with configuration options
     */
    public function __construct( KConfig $config = null) 
    {          
        parent::__construct($config);
        
        $this->_command = $config->command;
    }
    
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return void
	 */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'command' => KFactory::get('lib.koowa.command.event')
        ));

       parent::_initialize($config);
    }
    
	/**
     * Get the priority of the command
     *
     * @return  integer The command priority
     */
    public function getPriority()
    {
        return $this->_command->getPriority();
    }
     
    /**
     * Get the list of queries
     * 
     * @return 	array 	A list of the executed queries 
     */
    public function getQueries()
    {
        return $this->_queries;
    }
  
 	/**
     * Command handler
     * 
     * This function will add a mark to the profiler for each event dispatched and
     * will also capture all the database queries that are being executed.
     * 
     * @param   string      The command name
     * @param   object      The command context
     * @return  boolean     Always returns true
     */
    public function execute( $name, KCommandContext $context) 
    {
        if($context->caller instanceof KDatabaseAdapterInterface) 
        {        
            if(!empty($context->query)) {
                $this->_queries[] = $context;
            }
        }
        
        $this->mark($name);
        
        return $this->_command->execute($name, $context);
    }
  
	/**
	 * Overloaded call function
	 *
	 * @param  string 	The function name
	 * @param  array  	The function arguments
	 * @throws BadMethodCallException 	If method could not be found
	 * @return mixed The result of the function
	 */
	public function __call($method, array $arguments)
	{
		$object = $this->_command;

		//Check if the method exists
		if($object instanceof KObject)
		{
			$methods = $object->getMethods();
			$exists  = in_array($method, $methods);
		}
		else $exists = method_exists($object, $method);

		//Call the method if it exists
		if($exists)
		{
 			$result = null;

			// Call_user_func_array is ~3 times slower than direct method calls.
 		    switch(count($arguments))
 		    {
 		    	case 0 :
 		    		$result = $object->$method();
 		    		break;
 		    	case 1 :
 	              	$result = $object->$method($arguments[0]);
 		           	break;
 	           	case 2:
 	               	$result = $object->$method($arguments[0], $arguments[1]);
 		           	break;
 		      	case 3:
 	              	$result = $object->$method($arguments[0], $arguments[1], $arguments[2]);
 	               	break;
 	           	default:
 	             	// Resort to using call_user_func_array for many segments
 		            $result = call_user_func_array(array($object, $method), $arguments);
 	         }

 	         //Allow for method chaining through the decorator
 	         $class = get_class($object);
             if ($result instanceof $class) {
          		return $this;
             }

             return $result;
		}

		return parent::__call($method, $arguments);
	}
}