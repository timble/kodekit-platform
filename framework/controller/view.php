<?php
/**
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract View Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 */
abstract class ControllerView extends ControllerAbstract
{
	/**
	 * View object or identifier
	 *
	 * @var	string|object
	 */
	protected $_view;

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional Config object with configuration options.
	 */
	public function __construct(Config $config)
	{
		parent::__construct($config);

        //Force the view to the information found in the request
        $this->_view = $config->view;

		// Mixin the toolbar
		if($config->dispatch_events) {
            $this->mixin(new MixinToolbar($config->append(array('mixer' => $this))));
		}
	}
	
	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Config object with configuration options.
     * @return void
     */
    protected function _initialize(Config $config)
    {
        //Create permission identifier
        $permission       = clone $this->getIdentifier();
        $permission->path = array('controller', 'permission');

        $config->append(array(
            'view'      => $this->getIdentifier()->name,
            'behaviors' => array($permission),
        ));

        parent::_initialize($config);
    }

	/**
	 * Get the view object attached to the controller
	 *
	 * If we are dispatching this controller this function will check if the view folder exists. If not it will throw
     * an exception. This is a security measure to make sure we can only explicitly get data from views the have been
     * physically defined.
	 *
	 * @throws  ControllerExceptionNotFond If the view cannot be found. Only when controller is being dispatched.
     * @throws	\UnexpectedValueException	If the views doesn't implement the ViewInterface
	 * @return	ViewInterface
	 *
	 */
	public function getView()
	{
        if(!$this->_view instanceof ViewInterface)
		{
		    //Make sure we have a view identifier
		    if(!($this->_view instanceof ServiceIdentifier)) {
		        $this->setView($this->_view);
			}

			//Create the view
			$config = array(
                'media_url' => $this->getService('request')->getBaseUrl()->getPath().'/media',
			    'base_url'	=> $this->getService('request')->getUrl()->toString(HttpUrl::BASE ^ HttpUrl::USER ^ HttpUrl::PASS),
                'layout'    => $this->getRequest()->getQuery()->get('layout', 'alpha')
			);

			$this->_view = $this->getService($this->_view, $config);

            //Make sure the view implements ViewInterface
            if(!$this->_view instanceof ViewInterface)
            {
                throw new \UnexpectedValueException(
                    'View: '.get_class($this->_view).' does not implement ViewInterface'
                );
            }

			//Make sure the view exists if we are dispatching this controller
            if($this->isDispatched())
            {
                //if(!file_exists(dirname($this->_view->getIdentifier()->filepath))) {
                //    throw new ControllerExceptionNotFound('View : '.$this->_view->getName().' not found');
                //}
            }
		}

		return $this->_view;
	}

	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	An object that implements ServiceInterface, ServiceIdentifier object
	 * 					or valid identifier string
	 * @return	ControllerView
	 */
	public function setView($view)
	{
		if(!($view instanceof ViewInterface))
		{
			if(is_string($view) && strpos($view, '.') === false )
		    {
                $identifier			= clone $this->getIdentifier();
			    $identifier->path	= array('view', $view);
			    $identifier->name	= $this->getRequest()->getFormat();
			}
			else $identifier = $this->getIdentifier($view);

			$view = $identifier;
		}

		$this->_view = $view;

		return $this;
	}

	/**
	 * Render action
     *
     * This function will also set the rendered output in the response.
	 *
	 * @param	CommandContext	A command context object
	 * @return 	string|false 	The rendered output of the view or false if something went wrong
	 */
	protected function _actionRender(CommandContext $context)
	{
	    $view = $this->getView();

        //Push the params in the view
        foreach($context->param as $name => $value) {
            $view->set($name, $value);
        }

        //Push the content in the view
        $view->setContent($context->response->getContent());

        //Render the view
        $content = $view->render();

        //Set the data in the response
        $context->response
                ->setContent($content)
                ->setContentType($view->mimetype);

	    return $content;
	}

	/**
	 * Supports a simple form Fluent Interfaces. Allows you to set the request properties by using the request property
     * name as the method name.
	 *
	 * For example : $controller->layout('name')->format('html')->render();
	 *
	 * @param	string	Method name
	 * @param	array	Array containing all the arguments for the original call
	 * @return	ControllerView
	 *
	 * @see http://martinfowler.com/bliki/FluentInterface.html
	 */
	public function __call($method, $args)
	{
		//Check for layout, view or format property
        if(in_array($method, array('layout', 'format')))
        {
            $this->getRequest()->query->set($method, $args[0]);
            return $this;
        }

		return parent::__call($method, $args);
	}
}