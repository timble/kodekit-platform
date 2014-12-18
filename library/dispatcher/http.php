<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Http Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherHttp extends DispatcherAbstract implements ObjectInstantiable, ObjectMultiton
{
    /**
     * List of methods supported by the dispatcher
     *
     * @var array
     */
    protected $_methods = array();

    /**
     * Constructor.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the supported methods
        $this->_methods = ObjectConfig::unbox($config->methods);

        //Load the dispatcher translations
        $this->addCommandCallback('before.dispatch', '_loadTranslations');
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
            'methods'        => array('get', 'head', 'post', 'put', 'delete', 'options'),
            'behaviors'      => array('resettable'),
            'authenticators' => array('csrf'),
            'limit'          => array('max' => 1000, 'default' => 20)
         ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  DispatcherHttp
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        //Add the object alias to allow easy access to the singleton
        $manager->registerAlias($config->object_identifier, 'dispatcher');

        //Merge alias configuration into the identifier
        $config->append($manager->getIdentifier('dispatcher')->getConfig());

        //Instantiate the class
        $class     = $manager->getClass($config->object_identifier);
        $instance  = new $class($config);

        return $instance;
    }

    /**
     * Load the controller translations
     *
     * @param ControllerContextInterface $context
     * @return void
     */
    protected function _loadTranslations(ControllerContextInterface $context)
    {
        $package = $this->getIdentifier()->package;
        $domain  = $this->getIdentifier()->domain;

        if($domain) {
            $identifier = 'com://'.$domain.'/'.$package;
        } else {
            $identifier = 'com:'.$package;
        }

        $this->getObject('translator')->load($identifier);
    }

    /**
     * Dispatch the request
     *
     * Dispatch to a controller internally. Functions makes an internal sub-request, based on the information in
     * the request and passing along the context.
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return	mixed
     */
	protected function _actionDispatch(DispatcherContextInterface $context)
	{
        //Redirect if no view information can be found in the request
        if(!$context->request->query->has('view'))
        {
            $url = clone($context->request->getUrl());
            $url->query['view'] = $this->getController()->getView()->getName();

            $this->redirect($url);
        }
        else
        {
            $method = strtolower($context->request->getMethod());

            if (!in_array($method, $this->getHttpMethods())) {
                throw new DispatcherExceptionMethodNotAllowed('Method not allowed');
            }

            $view = $this->getRequest()->query->get('view', 'cmd');

            //Set the controller based on the view and pass the view
            $this->setController($view, array('view' => $view));

            //Execute the component method
            $this->execute($method, $context);
        }

        return parent::_actionDispatch($context);
	}

    /**
     * Redirect
     *
     * Redirect to a URL externally. Method performs a 301 (permanent) redirect. Method should be used to immediately
     * redirect the dispatcher to another URL after a GET request.
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionRedirect(DispatcherContextInterface $context)
    {
        $url = $context->param;

        $context->response->setStatus(DispatcherResponse::MOVED_PERMANENTLY);
        $context->response->setRedirect($url);
        $this->send();

        return false;
    }

    /**
     * Get method
     *
     * This function translates a GET request into a render action. If the request contains a limit the limit will
     * be set the enforced to the maximum limit. Default max limit is 100.
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return 	ModelEntityInterface	An entity object containing the modified data
     */
    protected function _actionGet(DispatcherContextInterface $context)
    {
        $controller = $this->getController();

        if($controller instanceof ControllerModellable)
        {
            $controller->getModel()->getState()->setProperty('limit', 'default', $this->getConfig()->limit->default);

            $limit = $this->getRequest()->query->get('limit', 'int');

            // Set to default if there is no limit. This is done for both unique and non-unique states
            // so that limit can be transparently used on unique state requests rendering lists.
            if(empty($limit)) {
                $limit = $this->getConfig()->limit->default;
            }

            //Force the maximum limit
            if($this->getConfig()->limit->max && $limit > $this->getConfig()->limit->max) {
                $limit = $this->getConfig()->limit->max;
            }

            $this->getRequest()->query->limit = $limit;
            $controller->getModel()->getState()->limit = $limit;
        }

        return $controller->execute('render', $context);
    }

    /**
     * Head method
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return ModelEntityInterface
     */
    protected function _actionHead(DispatcherContextInterface $context)
    {
        return $this->execute('get', $context);
    }

    /**
     * Post method
     *
     * This function translated a POST request action into an edit or add action. If the model state is unique a edit
     * action will be executed, if not unique an add action will be executed.
     *
     * If an _action parameter exists in the request data it will be used instead. If no action can be found an bad
     * request exception will be thrown.
     *
     * @param   DispatcherContextInterface $context	A dispatcher context object
     * @throws  DispatcherExceptionMethodNotAllowed   The action specified in the request is not allowed for the
     *          entity identified by the Request-URI. The response MUST include an Allow header containing a list of
     *          valid actions for the requested entity.
     *          ControllerExceptionRequestInvalid    The action could not be found based on the info in the request.
     * @return  ModelEntityInterface	An entity object containing the modified data
     */
    protected function _actionPost(DispatcherContextInterface $context)
    {
        $result     = false;
        $action     = null;
        $controller = $this->getController();

        if($controller instanceof ControllerModellable)
        {
            //Get the action from the request data
            if($context->request->data->has('_action'))
            {
                $action = strtolower($context->request->data->get('_action', 'alpha'));

                if(in_array($action, array('browse', 'read', 'render'))) {
                    throw new DispatcherExceptionMethodNotAllowed('Action: '.$action.' not allowed');
                }
            }
            else
            {
                //Determine the action based on the model state
                if($controller instanceof ControllerModellable) {
                    $action = $controller->getModel()->getState()->isUnique() ? 'edit' : 'add';
                }
            }

            //Throw exception if no action could be determined from the request
            if(!$action) {
                throw new ControllerExceptionRequestInvalid('Action not found');
            }

            $result = $controller->execute($action, $context);
        }
        else throw new DispatcherExceptionMethodNotAllowed('Method POST not allowed');
        
        return $result;
    }

    /**
     * Put method
     *
     * This function translates a PUT request into an edit or add action. Only if the model state is unique and the item
     * exists an edit action will be executed, if the entity does not exist and the state is unique an add action will
     * be executed.
     *
     * If the entity already exists it will be completely replaced based on the data available in the request.
     *
     * @param   DispatcherContextInterface $context	A dispatcher context object
     * @throws  ControllerExceptionRequestInvalid 	If the model state is not unique
     * @return 	ModelEntityInterface	    A entity object containing the modified data
     */
    protected function _actionPut(DispatcherContextInterface $context)
    {
        $result     = false;
        $action     = null;
        $controller = $this->getController();

        if($controller instanceof ControllerModellable)
        {
            if($controller->getModel()->getState()->isUnique())
            {
                $action = 'add';
                $entity = $controller->getModel()->fetch();

                if(!$entity->isNew())
                {
                    //Reset the row data
                    $entity->reset();
                    $action = 'edit';
                }
                else $entity = $controller->getModel()->create();

                //Set the row data based on the unique state information
                $state = $controller->getModel()->getState()->getValues(true);
                $entity->setProperties($state);
            }
            else throw new ControllerExceptionRequestInvalid('Resource not found');

            //Throw exception if no action could be determined from the request
            if(!$action) {
                throw new ControllerExceptionRequestInvalid('Resource not found');
            }

            $result = $controller->execute($action, $context);
        }
        else throw new DispatcherExceptionMethodNotAllowed('Method PUT not allowed');

        return $result;
    }

    /**
     * Delete method
     *
     * This function translates a DELETE request into a delete action.
     *
     * @param   DispatcherContextInterface $context	A dispatcher context object
     * @return 	ModelEntityInterface	A entity object containing the modified data
     */
    protected function _actionDelete(DispatcherContextInterface $context)
    {
        $result     = false;
        $controller = $this->getController();

        if($controller instanceof ControllerModellable) {
            $result = $controller->execute('delete', $context);
        } else {
            throw new DispatcherExceptionMethodNotAllowed('Method DELETE not allowed');
        }

        return $result;
    }

    /**
     * Options method
     *
     * @param   DispatcherContextInterface $context	A dispatcher context object
     * @return  string  The allowed actions; e.g., `GET, POST [add, edit, cancel, save], PUT, DELETE`
     */
    protected function _actionOptions(DispatcherContextInterface $context)
    {
        $agent   = $context->request->getAgent();
        $pattern = '#(?:Microsoft Office (?:Protocol|Core|Existence)|Microsoft-WebDAV)#i';

        if (preg_match($pattern, $agent)) {
            throw new DispatcherExceptionMethodNotAllowed('Method not allowed');
        }

        $methods = array();

        //Retrieve HTTP methods allowed by the dispatcher
        $actions = array_intersect($this->getActions(), $this->getHttpMethods());

        foreach($actions as $key => $action)
        {
            if($this->canExecute($action)) {
                $methods[$action] = $action;
            }
        }

        //Retrieve POST actions allowed by the controller
        if(in_array('post', $methods))
        {
            $actions = array_diff($this->getController()->getActions(), array('browse', 'read', 'render'));

            foreach($actions as $key => $action)
            {
                if(!$this->getController()->canExecute($action)) {
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

    /**
     * Send the response to the client
     *
     * - Set the affected entities in the payload for none-SAFE requests that return a successful response. Make an
     * exception for 204 No Content responses which should not return a response body.
     *
     * - Add an Allow header to the response if the status code is 405 METHOD NOT ALLOWED.
     *
     * {@inheritdoc}
     */
    protected function _actionSend(DispatcherContextInterface $context)
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();

        if (!$request->isSafe())
        {
            if ($response->isSuccess())
            {
                //Render the controller and set the result in the response body
                if($response->getStatusCode() !== HttpResponse::NO_CONTENT) {
                    $context->result = $this->getController()->execute('render', $context);
                }
            }
            else
            {
                //Add an Allow header to the reponse
                if($response->getStatusCode() === HttpResponse::METHOD_NOT_ALLOWED) {
                    try {
                        $this->_actionOptions($context);
                    }
                    catch (\Exception $e) {}
                }
            }
        }

        parent::_actionSend($context);
    }

    /**
     * Get the supported methods
     *
     * @return array
     */
    public function getHttpMethods()
    {
        return $this->_methods;
    }
}