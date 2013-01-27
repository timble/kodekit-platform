<?php
/**
 * @version		$Id$
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract View Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @uses        KInflector
 */
abstract class KControllerView extends KControllerAbstract
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
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

        //Force the view to the information found in the request
        $this->_view = $config->view;

		// Mixin the toolbar
		if($config->dispatch_events) {
            $this->mixin(new KMixinToolbar($config->append(array('mixer' => $this))));
		}
	}
	
	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'view' => $this->getIdentifier()->name,
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
	 * @throws  KControllerExceptionNotFond If the view cannot be found. Only when controller is being dispatched.
     * @throws	\UnexpectedValueException	If the views doesn't implement the KViewInterface
	 * @return	KViewInterface
	 *
	 */
	public function getView()
	{
        if(!$this->_view instanceof KViewInterface)
		{
		    //Make sure we have a view identifier
		    if(!($this->_view instanceof KServiceIdentifier)) {
		        $this->setView($this->_view);
			}

			//Create the view
			$config = array(
			    'media_url' => KRequest::root().'/media',
			    'base_url'	=> $this->getService('request')->getUrl()->getUrl(KHttpUrl::BASE),
                'layout'    => $this->getRequest()->getQuery()->get('layout', 'alpha')
			);

			$this->_view = $this->getService($this->_view, $config);

            //Make sure the view implements KViewInterface
            if(!$this->_view instanceof KViewInterface)
            {
                throw new \UnexpectedValueException(
                    'View: '.get_class($this->_view).' does not implement KViewInterface'
                );
            }

			//Make sure the view exists if we are dispatching this controller
            if($this->isDispatched())
            {
                if(!file_exists(dirname($this->_view->getIdentifier()->filepath))) {
                    throw new KControllerExceptionNotFound('View : '.$this->_view->getName().' not found');
                }
            }
		}

		return $this->_view;
	}

	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectServiceable, KServiceIdentifier object
	 * 					or valid identifier string
	 * @return	KControllerView
	 */
	public function setView($view)
	{
		if(!($view instanceof KViewAbstract))
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
	 * @param	KCommandContext	A command context object
	 * @return 	string|false 	The rendered output of the view or false if something went wrong
	 */
	protected function _actionRender(KCommandContext $context)
	{
	    $view = $this->getView();

        //Push the params in the view
        foreach($context->param as $name => $value) {
            $view->assign($name, $value);
        }

        //Render the view
        $content = $view->display();

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
	 * @return	KControllerView
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