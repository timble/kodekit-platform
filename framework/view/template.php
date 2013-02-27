<?php
/**
 * @package     Koowa_View
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Abstract Template View Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_View
 * @uses        KMixinClass
 * @uses        KTemplate
 * @uses        KService
 */
abstract class KViewTemplate extends KViewAbstract
{
    /**
     * Template object or identifier
     *
     * @var string|object
     */
    protected $_template;

    /**
     * Callback for escaping.
     *
     * @var string
     */
    protected $_escape;

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
     * The uniform resource locator
     *
     * @var object
     */
    protected $_mediaurl;

    /**
     * Layout name
     *
     * @var string
     */
    protected $_layout;

    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Set the media url
        if (!$config->media_url instanceof KHttpUrlInterface) {
            $this->_mediaurl = $this->getService('koowa:http.url', array('url' => $config->media_url));
        } else {
            $this->_mediaurl = $config->media_url;
        }

        //Set the auto assign state
        $this->_auto_assign = $config->auto_assign;

        //Set the data
        $this->_data = KConfig::unbox($config->data);

        //Set the user-defined escaping callback
        $this->setEscape($config->escape);

        //Set the layout
        $this->setLayout($config->layout);

        //Set the template object
        $this->_template = $config->template;

        //Set the template filters
        if (!empty($config->template_filters)) {
            $this->getTemplate()->attachFilter($config->template_filters);
        }

        //Add alias filter for media:// namespaced
        $this->getTemplate()->getFilter('alias')->addAlias(
            array('media://' => (string)$this->_mediaurl . '/'), KTemplateFilter::MODE_READ | KTemplateFilter::MODE_WRITE
        );
    }

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        //Clone the identifier
        $identifier = clone $this->getIdentifier();

        $config->append(array(
            'data'             => array(),
            'escape'           => 'htmlspecialchars',
            'layout'           => '',
            'template'         => $this->getName(),
            'template_filters' => array('shorttag', 'alias', 'variable'),
            'auto_assign'      => true,
            'media_url'        => '/media',
        ));

        parent::_initialize($config);
    }

    /**
     * Set a view data property
     *
     * @param   string  The property name.
     * @param   mixed   The property value.
     */
    public function __set($property, $value)
    {
        $this->_data[$property] = $value;
    }

    /**
     * Get a view data property
     *
     * @param   string  The property name.
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
     * Escapes a value for output in a view script.
     *
     * @param  mixed $var The output to escape.
     * @return mixed The escaped value.
     */
    public function escape($var)
    {
        return call_user_func($this->_escape, $var);
    }

    /**
     * Return the views output
     *
     * @return string     The output of the view
     */
    public function display()
    {
        $layout     = $this->getLayout();
        $format     = $this->getFormat();

        $identifier = clone $this->getIdentifier();
        $identifier->name = $layout.'.'.$format;

        $this->_content = $this->getTemplate()
            ->loadFile($identifier, $this->_data)
            ->render();

        return parent::display();
    }

    /**
     * Sets the view data
     *
     * @param   array The view data
     * @return  KViewAbstract
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
     * @param    string  The template name.
     * @return   KViewAbstract
     */
    public function setLayout($layout)
    {
        $this->_layout = $layout;
        return $this;
    }

    /**
     * Sets the _escape() callback.
     *
     * @param   mixed The callback for _escape() to use.
     * @return  KViewAbstract
     */
    public function setEscape($spec)
    {
        $this->_escape = $spec;
        return $this;
    }

    /**
     * Get the template object attached to the view
     *
     *  @throws	\UnexpectedValueException	If the template doesn't implement the KTemplateInterface
     * @return  KTemplateInterface
     */
    public function getTemplate()
    {
        if (!$this->_template instanceof KTemplateInterface)
        {
            //Make sure we have a template identifier
            if (!($this->_template instanceof KServiceIdentifier)) {
                $this->setTemplate($this->_template);
            }

            $options = array(
                'view' => $this
            );

            $this->_template = $this->getService($this->_template, $options);

            if(!$this->_template instanceof KTemplateInterface)
            {
                throw new \UnexpectedValueException(
                    'Template: '.get_class($this->_template).' does not implement KTemplateInterface'
                );
            }
        }

        return $this->_template;
    }

    /**
     * Method to set a template object attached to the view
     *
     * @param   mixed   An object that implements KObjectServiceable, an object that
     *                  implements KServiceIdentifierInterface or valid identifier string
     * @throws  \UnexpectedValueException    If the identifier is not a table identifier
     * @return  KViewAbstract
     */
    public function setTemplate($template)
    {
        if (!($template instanceof KTemplateInterface))
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
     * Get the view media url
     *
     * @return     object    A KHttpUrl object
     */
    public function getMediaUrl()
    {
        return $this->_mediaurl;
    }

    /**
     * Get a route based on a full or partial query string.
     *
     * This function adds the layout information to the route if a layout has been set
     *
     * @param    string    The query string used to create the route
     * @param     boolean    If TRUE create a fully qualified route. Default TRUE.
     * @param     boolean    If TRUE escapes the route for xml compliance. Default TRUE.
     * @return     string     The route
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
        return $this->display();
    }

    /**
     * Supports a simple form of Fluent Interfaces. Allows you to assign variables to the view by using the variable
     * name as the method name. If the method name is a setter method the setter will be called instead.
     *
     * For example : $view->layout('foo')->title('name')->display().
     *
     * @param   string  Method name
     * @param   array   Array containing all the arguments for the original call
     * @return  KViewAbstract
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