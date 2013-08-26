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
 * Abstract View Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerView extends ControllerAbstract implements ControllerViewable
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
	 * @param ObjectConfig $config 	An optional ObjectConfig object with configuration options.
	 */
	public function __construct(ObjectConfig $config)
	{
		parent::__construct($config);

        //Force the view to the information found in the request
        $this->_view = $config->view;

		// Mixin the toolbar
		if($config->dispatch_events)
        {
            $this->mixin('lib:controller.toolbar.mixin');

            //Attach the toolbars
            $this->registerCallback('before.render' , array($this, 'attachToolbars'), array($config->toolbars));
		}
	}
	
	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'view'      => $this->getIdentifier()->name,
            'behaviors' => array('permissible'),
            'toolbars'  => array()
        ));

        parent::_initialize($config);
    }

    /**
     * Attach the toolbars to the controller
     *
     * @param array $toolbars A list of toolbars
     * @return ControllerView
     */
    public function attachToolbars($toolbars)
    {
        if($this->getView() instanceof ViewHtml)
        {
            foreach($toolbars as $toolbar) {
                $this->attachToolbar($toolbar);
            }

            if($toolbars = $this->getToolbars())
            {
                $this->getView()
                    ->getTemplate()
                    ->attachFilter('toolbar', array('toolbars' => $toolbars));
            };
        }
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
	 */
	public function getView()
	{
        if(!$this->_view instanceof ViewInterface)
		{
		    //Make sure we have a view identifier
		    if(!($this->_view instanceof ObjectIdentifier)) {
		        $this->setView($this->_view);
			}

			//Create the view
			$config = array(
			    'url'	  => $this->getObject('request')->getUrl(),
                'layout'  => $this->getRequest()->getQuery()->get('layout', 'alpha')
			);

			$this->_view = $this->getObject($this->_view, $config);

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
                //if(!file_exists(dirname($this->_view->getIdentifier()->classpath))) {
                //    throw new ControllerExceptionNotFound('View : '.$this->_view->getName().' not found');
                //}
            }
		}

		return $this->_view;
	}

	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	$view   An object that implements ObjectInterface, ObjectIdentifier object
	 * 					        or valid identifier string
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
	 * @param	CommandContext	$context    A command context object
	 * @return 	string|false 	The rendered output of the view or false if something went wrong
	 */
	protected function _actionRender(CommandContext $context)
	{
        $view = $this->getView();

        //Push the params in the view
        $param = ObjectConfig::unbox($context->param);

        if(is_array($param))
        {
            foreach($context->param as $name => $value) {
                $view->set($name, $value);
            }
        }

        //Push the content in the view
        $view->setContent($context->response->getContent());

        //Render the view
        \JFactory::getLanguage()->load($this->getIdentifier()->package);
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
	 * @param	string	$method Method name
	 * @param	array	$args   Array containing all the arguments for the original call
	 * @return	ControllerView
	 *
	 * @see http://martinfowler.com/bliki/FluentInterface.html
	 */
	public function __call($method, $args)
	{
        if(!isset($this->_mixed_methods[$method]))
        {
		    //Check for layout, view or format property
            if(in_array($method, array('layout', 'format')))
            {
                $this->getRequest()->query->set($method, $args[0]);
                return $this;
            }
        }

		return parent::__call($method, $args);
	}
}