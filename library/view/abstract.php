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
 * Abstract View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\View
 */
abstract class ViewAbstract extends Object implements ViewInterface
{
    /**
     * Model object or identifier
     *
     * @var    string|object
     */
    protected $_model;

    /**
     * The uniform resource locator
     *
     * @var object
     */
    protected $_baseurl;

    /**
     * The content of the view
     *
     * @var string
     */
    protected $_content;

    /**
     * The mimetype
     *
     * @var string
     */
    public $mimetype = '';

    /**
     * Constructor
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //set the base url
        if (!$config->base_url instanceof HttpUrlInterface) {
            $this->_baseurl = $this->getObject('lib:http.url', array('url' => $config->base_url));
        } else {
            $this->_baseurl = $config->base_url;
        }

        $this->setContent($config->contents);
        $this->mimetype = $config->mimetype;

        $this->setModel($config->model);
    }

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'model'    => 'lib:model.empty',
            'contents' => '',
            'mimetype' => '',
            'base_url' => '',
        ));

        parent::_initialize($config);
    }

    /**
     * Render the view
     *
     * @return string  The output of the view
     */
    public function render()
    {
        $contents = $this->getContent();
        return trim($contents);
    }

    /**
     * Set a view property
     *
     * @param   string  $property The property name.
     * @param   mixed   $value    The property value.
     * @return ViewAbstract
     */
    public function set($property, $value)
    {
        $this->$property = $value;
        return $this;
    }

    /**
     * Get a view property
     *
     * @param   string  $property The property name.
     * @return  string  The property value.
     */
    public function get($property)
    {
        return isset($this->$property) ? $this->$property : null;
    }

    /**
     * Get the name
     *
     * @return  string  The name of the object
     */
    public function getName()
    {
        $total = count($this->getIdentifier()->path);
        return $this->getIdentifier()->path[$total - 1];
    }

    /**
     * Get the format
     *
     * @return string   The format of the view
     */
    public function getFormat()
    {
        return $this->getIdentifier()->name;
    }

    /**
     * Get the content
     *
     * @return  string The content of the view
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Get the contents
     *
     * @param  string $contents The contents of the view
     * @return ViewAbstract
     */
    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }

    /**
     * Get the model object attached to the view
     *
     * @throws	\UnexpectedValueException	If the model doesn't implement the ModelInterface
     * @return	ModelAbstract
     */
    public function getModel()
    {
        if(!$this->_model instanceof ModelInterface)
        {
            if(!($this->_model instanceof ObjectIdentifier)) {
                $this->setModel($this->_model);
            }

            $this->_model = $this->getObject($this->_model);

            if(!$this->_model instanceof ModelInterface)
            {
                throw new \UnexpectedValueException(
                    'Model: '.get_class($this->_model).' does not implement ModelInterface'
                );
            }
        }

        return $this->_model;
    }

    /**
     * Method to set a model object attached to the controller
     *
     * @param	mixed	$model An object that implements ObjectInterface, ObjectIdentifier object
     * 					       or valid identifier string
     * @return	ViewAbstract
     */
    public function setModel($model)
    {
        if(!($model instanceof ModelInterface))
        {
            if(is_string($model) && strpos($model, '.') === false )
            {
                // Model names are always plural
                if(StringInflector::isSingular($model)) {
                    $model = StringInflector::pluralize($model);
                }

                $identifier			= clone $this->getIdentifier();
                $identifier->path	= array('model');
                $identifier->name	= $model;
            }
            else $identifier = $this->getIdentifier($model);

            $model = $identifier;
        }

        $this->_model = $model;

        return $this;
    }

    /**
     * Get a route based on a full or partial query string
     *
     * 'option', 'view' and 'layout' can be omitted. The following variations will all result in the same route :
     *
     * - foo=bar
     * - option=com_mycomp&view=myview&foo=bar
     *
     * In templates, use route()
     *
     * @param   string|array $route  The query string used to create the route
     * @param   boolean      $fqr    If TRUE create a fully qualified route. Default TRUE.
     * @param   boolean      $escape If TRUE escapes the route for xml compliance. Default TRUE.
     * @return  string The route
     */
    public function getRoute($route, $fqr = null, $escape = null)
    {
        //Parse route
        $parts = array();

        //@TODO : Check if $route if valid. Throw exception if not.
        if(is_string($route)) {
            parse_str(trim($route), $parts);
        } else {
            $parts = $route;
        }

        //Check to see if there is component information in the route if not add it
        if (!isset($parts['option'])) {
            $parts['option'] = 'com_' . $this->getIdentifier()->package;
        }

        //Add the view information to the route if it's not set
        if (!isset($parts['view'])) {
            $parts['view'] = $this->getName();
        }

        //Add the format information to the route only if it's not 'html'
        if (!isset($parts['format'])) {
            $parts['format'] = $this->getIdentifier()->name;
        }

        //Add the model state only for routes to the same view
        if ($parts['view'] == $this->getName())
        {
            $states = array();
            foreach($this->getModel()->getState() as $name => $state)
            {
                if($state->default != $state->value) {
                    $states[$name] = $state->value;
                }
            }

            $parts = array_merge($states, $parts);
        }

        //Create the route
        $route = $this->getObject('lib:dispatcher.router.route', array(
            'url'    => '?'.http_build_query($parts),
            'escape' => $escape === null || $escape === true ? true : false
        ));

        //Add the host and the schema
        if ($fqr === null || $fqr === true)
        {
            $route->scheme = $this->getBaseUrl()->scheme;
            $route->host   = $this->getBaseUrl()->host;
        }

        return $route;
    }

    /**
     * Get the view base url
     *
     * @return  HttpUrl  A HttpUrl object
     */
    public function getBaseUrl()
    {
        return $this->_baseurl;
    }

    /**
     * Returns the views output
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}