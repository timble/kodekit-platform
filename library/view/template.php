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
 * Abstract Template View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\View
 */
abstract class ViewTemplate extends ViewAbstract
{
    /**
     * Template object or identifier
     *
     * @var string|object
     */
    protected $_template;

    /**
     * Auto assign
     *
     * @var boolean
     */
    protected $_auto_assign;

    /**
     * The assigned data
     *
     * @var boolean
     */
    protected $_data;

    /**
     * Layout name
     *
     * @var string
     */
    protected $_layout;

    /**
     * Constructor
     *
     * @param   object  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the auto assign state
        $this->_auto_assign = $config->auto_assign;

        //Set the data
        $this->_data = ObjectConfig::unbox($config->data);

        //Set the layout
        $this->setLayout($config->layout);

        //Set the template object
        $this->_template = $config->template;

        //Attach the template filters
        $filters = (array)ObjectConfig::unbox($config->template_filters);

        foreach ($filters as $key => $value)
        {
            if (is_numeric($key)) {
                $this->getTemplate()->attachFilter($value);
            } else {
                $this->getTemplate()->attachFilter($key, $value);
            }
        }
    }

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        //Clone the identifier
        $identifier = clone $this->getIdentifier();

        $config->append(array(
            'data'             => array(),
            'layout'           => '',
            'template'         => $this->getName(),
            'template_filters' => array('shorttag', 'function', 'url', 'decorator'),
            'auto_assign'      => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Set a view data property
     *
     * @param   string  $property The property name.
     * @param   mixed   $value    The property value.
     */
    public function __set($property, $value)
    {
        $this->_data[$property] = $value;
    }

    /**
     * Get a view data property
     *
     * @param   string  $property The property name.
     * @return  string  The property value.
     */
    public function __get($property)
    {
        $result = null;
        if (isset($this->_data[$property])) {
            $result = $this->_data[$property];
        }

        return $result;
    }

    /**
     * Return the views output
     *
     * @return string     The output of the view
     */
    public function render()
    {
        $layout     = $this->getLayout();
        $format     = $this->getFormat();

        $identifier = clone $this->getIdentifier();
        $identifier->name = $layout.'.'.$format;

        $this->_content = $this->getTemplate()
            ->loadFile($identifier, $this->_data)
            ->render();

        return parent::render();
    }

    /**
     * Sets the view data
     *
     * @param   array $data The view data
     * @return  ViewAbstract
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * Get the view data
     *
     * @return  array   The view data
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Get the layout
     *
     * @return string The layout name
     */
    public function getLayout()
    {
        return empty($this->_layout) ? 'default' : $this->_layout;
    }

    /**
     * Sets the layout name to use
     *
     * @param    string  $layout The template name.
     * @return   ViewAbstract
     */
    public function setLayout($layout)
    {
        $this->_layout = $layout;
        return $this;
    }

    /**
     * Get the template object attached to the view
     *
     *  @throws	\UnexpectedValueException	If the template doesn't implement the TemplateInterface
     * @return  TemplateInterface
     */
    public function getTemplate()
    {
        if (!$this->_template instanceof TemplateInterface)
        {
            //Make sure we have a template identifier
            if (!($this->_template instanceof ObjectIdentifier)) {
                $this->setTemplate($this->_template);
            }

            $options = array(
                'view' => $this
            );

            $this->_template = $this->getObject($this->_template, $options);

            if(!$this->_template instanceof TemplateInterface)
            {
                throw new \UnexpectedValueException(
                    'Template: '.get_class($this->_template).' does not implement TemplateInterface'
                );
            }
        }

        return $this->_template;
    }

    /**
     * Method to set a template object attached to the view
     *
     * @param   mixed   $template An object that implements ObjectInterface, an object that implements
     *                            ObjectIdentifierInterface or valid identifier string
     * @throws  \UnexpectedValueException    If the identifier is not a table identifier
     * @return  ViewAbstract
     */
    public function setTemplate($template)
    {
        if (!($template instanceof TemplateInterface))
        {
            if (is_string($template) && strpos($template, '.') === false)
            {
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('template');
                $identifier->name = $template;
            }
            else $identifier = $this->getIdentifier($template);

            $template = $identifier;
        }

        $this->_template = $template;

        return $this;
    }

    /**
     * Get a route based on a full or partial query string.
     *
     * This function adds the layout information to the route if a layout has been set
     *
     * @param string $route   The query string used to create the route
     * @param boolean $fqr    If TRUE create a fully qualified route. Default TRUE.
     * @param boolean $escape If TRUE escapes the route for xml compliance. Default TRUE.
     * @return  string The route
     */
    public function getRoute($route = '', $fqr = null, $escape = null)
    {
        $route = parent::getRoute($route, $fqr, $escape);

        if (!isset($route->query['layout']) && !empty($this->_layout))
        {
            if ($route->query['view'] == $this->getName()) {
                $route->query['layout'] = $this->getLayout();
            }
        }

        return $route;
    }

    /**
     * Execute and return the views output
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Supports a simple form of Fluent Interfaces. Allows you to assign variables to the view by using the variable
     * name as the method name. If the method name is a setter method the setter will be called instead.
     *
     * For example : $view->layout('foo')->title('name')->render().
     *
     * @param   string  $method Method name
     * @param   array   $args   Array containing all the arguments for the original call
     * @return  ViewAbstract
     *
     * @see http://martinfowler.com/bliki/FluentInterface.html
     */
    public function __call($method, $args)
    {
        //If one argument is passed we assume a setter method is being called 
        if (count($args) == 1)
        {
            if (method_exists($this, 'set' . ucfirst($method))) {
                return $this->{'set' . ucfirst($method)}($args[0]);
            } else {
                return $this->$method = $args[0];
            }
        }

        return parent::__call($method, $args);
    }
}