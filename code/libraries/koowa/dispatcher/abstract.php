<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract controller dispatcher
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 */
abstract class KDispatcherAbstract extends KControllerAbstract
{
	/**
	 * Controller object or identifier (com://APP/COMPONENT.controller.NAME)
	 *
	 * @var	string|object
	 */
	protected $_controller;

	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		//Set the controller
		$this->_controller = $config->controller;
	}

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
        	'controller' => $this->getIdentifier()->package,
    		'request'	 => KRequest::get('get', 'string'),
        ))->append(array (
            'request' 	 => array('format' => KRequest::format() ? KRequest::format() : 'html')
        ));

        parent::_initialize($config);
    }

	/**
	 * Method to get a controller object
	 *
	 * @return	KControllerAbstract
	 */
	public function getController()
	{
		if(!($this->_controller instanceof KControllerAbstract))
		{
		    //Make sure we have a controller identifier
		    if(!($this->_controller instanceof KServiceIdentifier)) {
		        $this->setController($this->_controller);
			}

		    $config = array(
        		'request' 	   => $this->_request,
			    'dispatched'   => true
        	);

			$this->_controller = $this->getService($this->_controller, $config);
		}

		return $this->_controller;
	}

	/**
	 * Method to set a controller object attached to the dispatcher
	 *
	 * @param	mixed	An object that implements KObjectServiceable, KServiceIdentifier object
	 * 					or valid identifier string
	 * @throws	KDispatcherException	If the identifier is not a controller identifier
	 * @return	KDispatcherAbstract
	 */
	public function setController($controller)
	{
		if(!($controller instanceof KControllerAbstract))
		{
			if(is_string($controller) && strpos($controller, '.') === false )
		    {
		        // Controller names are always singular
			    if(KInflector::isPlural($controller)) {
				    $controller = KInflector::singularize($controller);
			    }

			    $identifier			= clone $this->getIdentifier();
			    $identifier->path	= array('controller');
			    $identifier->name	= $controller;
			}
		    else $identifier = $this->getIdentifier($controller);

			if($identifier->path[0] != 'controller') {
				throw new KDispatcherException('Identifier: '.$identifier.' is not a controller identifier');
			}

			$controller = $identifier;
		}

		$this->_controller = $controller;

		return $this;
	}

    /**
     * Get the command chain context
     *
     * This functions sets the command subject as the mixer in the context
     *
     * @return  KCommandContext
     */
    public function getCommandContext()
    {
        $context = $this->getCommandChain()->getContext();
        $context->setSubject($this);

        $context->response = $this->getService('koowa:dispatcher.response.default');

        return $context;
    }

	/**
	 * Dispatch the controller
	 *
	 * @param   object		A command context object
	 * @return	mixed
	 */
	protected function _actionDispatch(KCommandContext $context)
	{
	    $action = KRequest::get('post.action', 'cmd', strtolower(KRequest::method()));

	    if(KRequest::method() != KHttpRequest::GET) {
            $context->data = KRequest::get(strtolower(KRequest::method()), 'raw');;
        }

	    $result = $this->getController()->execute($action, $context);

        return $result;
	}
}