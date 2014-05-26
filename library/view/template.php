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
    protected $_auto_fetch;

    /**
     * Layout name
     *
     * @var string
     */
    protected $_layout;

    /**
     * Constructor
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the auto assign state
        $this->_auto_fetch = $config->auto_fetch;

        //Set the layout
        $this->setLayout($config->layout);

        //Set the template object
        $this->setTemplate($config->template);

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

        //Fetch the view data before rendering
        $this->addCommandCallback('before.render', '_fetchData');
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
        $config->append(array(
            'layout'           => '',
            'template'         => $this->getName(),
            'template_filters' => array('shorttag', 'function', 'url', 'decorator'),
            'auto_fetch'       => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Return the views output
     *
     * @param ViewContext	$context A view context object
     * @return string  The output of the view
     */
    protected function _actionRender(ViewContext $context)
    {
        $layout     = $this->getLayout();
        $format     = $this->getFormat();
        $data       = $this->getData();

        //Handle partial layout paths
        if (is_string($layout) && strpos($layout, '.') === false)
        {
            $identifier = $this->getIdentifier()->toArray();
            $identifier['name'] = $layout;

            $layout = (string) $this->getIdentifier($identifier);
        }

        $this->_content = (string) $this->getTemplate()
            ->load((string) $layout.'.'.$format)
            ->compile()
            ->evaluate($data)
            ->render();

        return parent::_actionRender($context);
    }

    /**
     * Fetch the view data
     *
     * This function will always fetch the model state. Model data will only be fetched if the auto_fetch property is
     * set to TRUE.
     *
     * @param ViewContext	$context A view context object
     * @return void
     */
    protected function _fetchData(ViewContext $context)
    {
        $model = $this->getModel();

        //Auto-assign the state to the view
        $context->data->state = $model->getState();

        //Auto-assign the data from the model
        if($this->_auto_fetch)
        {
            //Get the view name
            $name = $this->getName();

            //Assign the data of the model to the view
            if(StringInflector::isPlural($name)) {
                $context->data->total = $model->count();
            }

            $context->data->$name = $model->fetch();
        }
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
                $identifier = $this->getIdentifier()->toArray();
                $identifier['path'] = array('template');
                $identifier['name'] = $template;
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
        //@TODO : Check if $route if valid. Throw exception if not.
        if(is_string($route)) {
            parse_str(trim($route), $parts);
        } else {
            $parts = $route;
        }

        // Check to see if there is component information in the route if not add it
        if (!isset($parts['option'])) {
            $parts['option'] = 'com_' . $this->getIdentifier()->package;
        }

        // Add the view information to the route if it's not set
        if (!isset($parts['view'])) {
            $parts['view'] = $this->getName();
        }

        if (!isset($parts['layout']) && !empty($this->_layout))
        {
            if ((substr($parts['option'], 4) == $this->getIdentifier()->package) && ($parts['view'] == $this->getName())) {
                $parts['layout'] = $this->getLayout();
            }
        }

        return parent::getRoute($parts, $fqr, $escape);
    }
}