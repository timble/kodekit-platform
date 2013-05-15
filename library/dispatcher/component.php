<?php
/**
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Controller Dispatcher Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 */
class DispatcherComponent extends DispatcherAbstract implements ObjectInstantiable
{
	/**
	 * Constructor.
	 *
	 * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
	 */
	public function __construct(ObjectConfig $config)
	{
		parent::__construct($config);

        //Authenticate none safe requests
        $this->registerCallback('before.post'  , array($this, 'authenticateRequest'));
        $this->registerCallback('before.put'   , array($this, 'authenticateRequest'));
        $this->registerCallback('before.delete', array($this, 'authenticateRequest'));

        //Sign GET request with a cookie token
        $this->registerCallback('after.get' , array($this, 'signResponse'));

        //Force the controller to the information found in the request
        if($config->request->query->has('view')) {
            $this->_controller = $config->request->query->get('view', 'alpha');
        }
	}

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
    	$config->append(array(
        	'controller' => $this->getIdentifier()->package,
            'behaviors'  => array('persistable', 'resettable')
         ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return DispatcherComponent
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        if (!$manager->isRegistered($config->object_identifier))
        {
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->registerAlias('component', $config->object_identifier);
        }

        return $manager->getObject($config->object_identifier);
    }

    /**
     * Check the request token to prevent CSRF exploits
     *
     * @param   CommandContext $context The command context
     * @return  boolean Returns FALSE if the check failed. Otherwise TRUE.
     */
    public function authenticateRequest(CommandContext $context)
    {
        $request = $context->request;
        $user    = $context->user;

        //Check referrer
        if(!$request->getReferrer()) {
            throw new ControllerExceptionForbidden('Invalid Request Referrer');
        }

        //Check cookie token
        if($request->getToken() !== $request->cookies->get('_token', 'md5')) {
            throw new ControllerExceptionForbidden('Invalid Cookie Token');
        }

        //Check session token
        if($user->isAuthentic())
        {
            if( $request->getToken() !== $user->session->getToken()) {
                throw new ControllerExceptionForbidden('Invalid Session Token');
            }
        }

        return true;
    }

    /**
     * Sign the response with a token
     *
     * @param	CommandContext	$context A command context object
     */
    public function signResponse(CommandContext $context)
    {
        if(!$context->response->isError())
        {
            $token = $context->user->session->getToken();

            $context->response->headers->addCookie($this->getObject('lib:http.cookie', array(
                'name'   => '_token',
                'value'  => $token,
                'path'   => $context->request->getBaseUrl()->getPath() ?: '/'
            )));

            $context->response->headers->set('X-Token', $token);
        }
    }

    /**
     * Dispatch the request
     *
     * Dispatch to a controller internally. Functions makes an internal sub-request, based on the information in
     * the request and passing along the context.
     *
     * @param   CommandContext	$context A command context object
     * @return	mixed
     */
	protected function _actionDispatch(CommandContext $context)
	{
        //Redirect if no view information can be found in the request
        if(!$context->request->query->has('view'))
        {
            $url = clone($context->request->getUrl());
            $url->query['view'] = $this->getController()->getView()->getName();

            return $this->redirect($url);
        }

        //Execute the component method
        $method = strtolower($context->request->getMethod());
	    $result = $this->execute($method, $context);

        return $result;
	}

    /**
     * Get method
     *
     * This function translates a GET request into a render action.
     *
     * @param	CommandContext	$context    A command context object
     * @return 	DatabaseRow(Set)Interface	A row(set) object containing the modified data
     */
    protected function _actionGet(CommandContext $context)
    {
        $controller = $this->getController();
        return $controller->execute('render', $context);
    }

    /**
     * Post method
     *
     * This function translated a POST request action into an edit or add action. If the model state is unique a edit
     * action will be executed, if not unique an add action will be executed.
     *
     * @param	CommandContext	$context    A command context object
     * @throws  DispatcherExceptionActionNotAllowed    The action specified in the request is not allowed for the
     *          resource identified by the Request-URI. The response MUST include an Allow header containing a list of
     *          valid actions for the requested resource.
     * @return 	DatabaseRow(Set)Interface	A row(set) object containing the modified data
     */
    protected function _actionPost(CommandContext $context)
    {
        $controller = $this->getController();

        if($context->request->data->has('_action'))
        {
            $action = strtolower($context->request->data->get('_action', 'alpha'));

            if(in_array($action, array('browse', 'read', 'render'))) {
                throw new DispatcherExceptionActionNotAllowed('Action: '.$action.' not allowed');
            }
        }
        else $action = $controller->getModel()->getState()->isUnique() ? 'edit' : 'add';

        return $controller->execute($action, $context);
    }

    /**
     * Put method
     *
     * This function translates a PUT request into an edit or add action. Only if the model state is unique and the item
     * exists an edit action will be executed, if the resources doesn't exist and the state is unique an add action will
     * be executed.
     *
     * If the resource already exists it will be completely replaced based on the data available in the request.
     *
     * @param	CommandContext	$context        A command context object
     * @throws  ControllerExceptionBadRequest 	If the model state is not unique
     * @return 	DatabaseRow(set)Ineterface	    A row(set) object containing the modified data
     */
    protected function _actionPut(CommandContext $context)
    {
        $controller = $this->getController();
        $entity     = $controller->getModel()->getRow();

        if($controller->getModel()->getState()->isUnique())
        {
            $action = 'add';
            if(!$entity->isNew())
            {
                //Reset the row data
                $entity->reset();
                $action = 'edit';
            }

            //Set the row data based on the unique state information
            $state = $controller->getModel()->getState()->toArray(true);
            $entity->setData($state);

            $entity = $controller->execute($action, $context);
        }
        else throw new ControllerExceptionBadRequest('Resource not found');

        return $entity;
    }

    /**
     * Delete method
     *
     * This function translates a DELETE request into a delete action.
     *
     * @param	CommandContext	$context    A command context object
     * @return 	DatabaseRow(Set)Interface	A row(set) object containing the modified data
     */
    protected function _actionDelete(CommandContext $context)
    {
        $controller = $this->getController();

        return $controller->execute('delete', $context);
    }

    /**
     * Options method
     *
     * @param	CommandContext	$context    A command context object
     * @return  string  The allowed actions; e.g., `GET, POST [add, edit, cancel, save], PUT, DELETE`
     */
    protected function _actionOptions(CommandContext $context)
    {
        $methods = array();

        //Retrieve HTTP methods allowed by the dispatcher
        $actions = array_diff($this->getActions(), array('dispatch'));

        foreach($actions as $key => $action)
        {
            if($this->isPermitted($action)) {
                $methods[$action] = $action;
            }
        }

        //Retrieve POST actions allowed by the controller
        if(in_array('post', $methods))
        {
            $actions = array_diff($this->getController()->getActions(), array('browse', 'read', 'render'));

            foreach($actions as $key => $action)
            {
                if(!$this->getController()->isPermitted($action)) {
                    unset($actions[$key]);
                }
            }

            sort($actions);

            $methods['post'] = array_diff($actions, $methods);
        }

        //Render to string
        $result = '';
        foreach($methods as $method => $actions)
        {
            $result .= strtoupper($method). ' ';
            if(is_array($actions) && !empty($actions)) {
                $result .= '['.implode(', ', $actions).'] ';
            }
        }

        $context->response->headers->set('Allow', $result);
    }
}