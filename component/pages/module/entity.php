<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Entity Module
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
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
            //Force layout type to 'mod' to force using the module locator for partial layouts
            $layout = $this->getLayout();

            if (is_string($layout) && strpos($layout, '.') === false)
            {
                $identifier = $this->getIdentifier()->toArray();
                $identifier['type'] = 'mod';
                $identifier['name'] = $layout;
                unset($identifier['path'][0]);

                $layout = (string) $this->getIdentifier($identifier);
            }

            //Create the controller
            $query = $this->getParameters();
            $query['layout'] = $layout;

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