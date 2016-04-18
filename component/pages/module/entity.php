<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Entity Module
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
abstract class ModuleEntity extends ModuleAbstract
{
    /**
     * Controller object or identifier
     *
     * @var	string|object
     */
    protected $_controller;

    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the controller
        $this->_controller = $config->controller;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'controller' => 'com:'.$this->getIdentifier()->package.'.controller.'.$this->getName(),
        ));

        parent::_initialize($config);
    }

    protected function _actionRender(Library\ViewContext $context)
    {
        $controller = $this->getController();
        $view       = $controller->getView();

        //Configure the view
        $view->setParameters($context->getParameters()->toArray());
        $view->setTitle($this->getTitle());

        //Render the controller
        $html = $controller->render(array('module' => $this->module));

        //Set the html in the module
        $this->setContent($html);

        return $html;
    }

    public function getController()
    {
        if(!$this->_controller instanceof Library\ControllerModellable)
        {
            //Create the controller
            $query = $this->getParameters();
            $query['layout'] = $this->qualifyLayout($this->getLayout());

            $this->_controller = $this->getObject($this->_controller,  array(
                'request' => array('query' => $query)
            ));

            if(!$this->_controller instanceof Library\ControllerModellable)
            {
                throw new \UnexpectedValueException(
                    'Controller: '.get_class($this->_controller).' does not implement ControllerModellableInterface'
                );
            }
        }

        return $this->_controller;
    }
}