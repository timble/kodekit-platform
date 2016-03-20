<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Abstract View Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
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
     * List of formats supported by the controller
     *
     * @var array
     */
    protected $_formats;

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

        //Set the supported formats
        $this->_formats = ObjectConfig::unbox($config->formats);
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
            'formats'   => array('html'),
            'view'      => $this->getIdentifier()->name,
            'toolbars'  => array(),
            'behaviors' => array('localizable'),
        ))->append(array(
            'behaviors'     => array(
                'commandable' => array('toolbars' => $config->toolbars),
            ),
        ));

        parent::_initialize($config);
    }

    /**
     * Get the view object attached to the controller
     *
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
                'url'	     => clone $this->getObject('request')->getUrl(),
                'layout'     => $this->getRequest()->getQuery()->get('layout', 'identifier'),
                'auto_fetch' => $this instanceof ControllerModellable
            );

            $this->_view = $this->getObject($this->_view, $config);

            //Make sure the view implements ViewInterface
            if(!$this->_view instanceof ViewInterface)
            {
                throw new \UnexpectedValueException(
                    'View: '.get_class($this->_view).' does not implement ViewInterface'
                );
            }
        }

        return $this->_view;
    }

    /**
     * Get the supported formats
     *
     * Method dynamically adds the 'json' format if the user is authentic.
     *
     * @return array
     */
    public function getFormats()
    {
        $result = $this->_formats;
        if($this->getUser()->isAuthentic()) {
            $result[] = 'json';
        }

        return $result;
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
                $identifier			= $this->getIdentifier()->toArray();
                $identifier['path']	= array('view', $view);
                $identifier['name']	= $this->getRequest()->getFormat();

                $identifier = $this->getIdentifier($identifier);
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
     * This function will check if the format is supported and if not throw a 406 Not Accepted exception. It will also
     * set the rendered output in the response after it has been created.
     *
     * @param	ControllerContextInterface	$context    A controller context object
     * @throws  ControllerExceptionFormatNotSupported If the requested format is not supported for the resource
     * @return 	string|false 	The rendered output of the view or false if something went wrong
     */
    protected function _actionRender(ControllerContextInterface $context)
    {
        $format = $this->getRequest()->getFormat();

        //Check if the format is supported
        if(in_array($format, $this->getFormats()))
        {
            $view = $this->getView();

            //Push the content in the view
            $view->setContent($context->response->getContent());

            $param = ObjectConfig::unbox($context->param);

            if(is_array($param)) {
                $data = (array) $param;
            } else {
                $data = array();
            }

            $content = $view->render($data);

            //Set the data in the response
            $context->response->setContent($content, $view->mimetype);
        }
        else throw new ControllerExceptionFormatNotSupported('Format: '.$format.' not supported');

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
            if(in_array($method, array('layout', 'view', 'format')))
            {
                if($method == 'view') {
                    $this->setView($args[0]);
                }

                if($method == 'format') {
                    $this->getRequest()->setFormat($args[0]);
                }

                if($method == 'layout') {
                    $this->getRequest()->getQuery()->set($method, $args[0]);
                }

                return $this;
            }
        }

        return parent::__call($method, $args);
    }
}
