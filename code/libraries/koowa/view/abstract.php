<?php
/**
 * @version		$Id$
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract View Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package		Koowa_View
 * @uses		KMixinClass
 * @uses 		KTemplate
 */
abstract class KViewAbstract extends KObject
{
	/**
	 * Model identifier (com://APP/COMPONENT.model.NAME)
	 *
	 * @var	string|object
	 */
	protected $_model;

	/**
	 * The output of the view
	 *
	 * @var string
	 */
	public $output = '';

	/**
	 * The mimetype
	 *
	 * @var string
	 */
	public $mimetype = '';

	/**
     * Layout name
     *
     * @var     string
     */
    protected $_layout;

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config = null)
	{
		//If no config is passed create it
		if(!isset($config)) $config = new KConfig();

		parent::__construct($config);

		//Set the output if defined in the config
		$this->output = $config->output;

		//Set the mimetype of defined in the config
		$this->mimetype = $config->mimetype;

		// set the model
		$this->setModel($config->model);

		// set the layout
        $this->setLayout($config->layout);
	}

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
			'model'   	=> $this->getName(),
	    	'output'	=> '',
    		'mimetype'	=> '',
            'layout'    => 'default',
	  	));

        parent::_initialize($config);
    }

	/**
	 * Get the name
	 *
	 * @return 	string 	The name of the object
	 */
	public function getName()
	{
		$total = count($this->getIdentifier()->path);
		return $this->getIdentifier()->path[$total - 1];
	}

	/**
	 * Get the format
	 *
	 * @return 	string 	The format of the view
	 */
	public function getFormat()
	{
		return $this->getIdentifier()->name;
	}

	/**
	 * Return the views output
 	 *
	 * @return string 	The output of the view
	 */
	public function display()
	{
		return $this->output;
	}

	/**
	 * Get the model object attached to the contoller
	 *
	 * @return	KModelAbstract
	 */
	public function getModel()
	{
		if(!$this->_model instanceof KModelAbstract)
		{
			//Make sure we have a model identifier
		    if(!($this->_model instanceof KServiceIdentifier)) {
		        $this->setModel($this->_model);
			}

		    $this->_model = $this->getService($this->_model);
		}

		return $this->_model;
	}

	/**
	 * Method to set a model object attached to the view
	 *
	 * @param	mixed	An object that implements KObjectServiceable, KServiceIdentifier object
	 * 					or valid identifier string
	 * @throws	KViewException	If the identifier is not a table identifier
	 * @return	KViewAbstract
	 */
    public function setModel($model)
	{
		if(!($model instanceof KModelAbstract))
		{
	        if(is_string($model) && strpos($model, '.') === false )
		    {
			    // Model names are always plural
			    if(KInflector::isSingular($model)) {
				    $model = KInflector::pluralize($model);
			    }

			    $identifier			= clone $this->getIdentifier();
			    $identifier->path	= array('model');
			    $identifier->name	= $model;
			}
			else $identifier = $this->getIdentifier($model);

			if($identifier->path[0] != 'model') {
				throw new KControllerException('Identifier: '.$identifier.' is not a model identifier');
			}

			$model = $identifier;
		}

		$this->_model = $model;

		return $this;
	}

 	/**
     * Get the layout.
     *
     * @return string The layout name
     */
    public function getLayout()
    {
        return $this->_layout;
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
	 * Create a route based on a full or partial query string
	 *
	 * index.php, option, view and layout can be ommitted. The following variations
	 * will all result in the same route
	 *
	 * - foo=bar
	 * - option=com_mycomp&view=myview&foo=bar
	 * - index.php?option=com_mycomp&view=myview&foo=bar
	 *
	 * If the route starts '&' the information will be appended to the current URL.
	 *
	 * In templates, use @route()
	 *
	 * @param	string	The query string used to create the route
	 * @return 	string 	The route
	 */
	public function createRoute( $route = '')
	{
		$route = trim($route);

		// Special cases
		if($route == 'index.php' || $route == 'index.php?')
		{
			$result = $route;
		}
		else if (substr($route, 0, 1) == '&')
		{
			$url   = clone KRequest::url();
			$vars  = array();
			parse_str($route, $vars);

			$url->setQuery(array_merge($url->getQuery(true), $vars));

			$result = 'index.php?'.$url->getQuery();
		}
		else
		{
			// Strip 'index.php?'
			if(substr($route, 0, 10) == 'index.php?') {
				$route = substr($route, 10);
			}

			// Parse route
			$parts = array();
			parse_str($route, $parts);
			$result = array();

			// Check to see if there is component information in the route if not add it
			if(!isset($parts['option'])) {
				$result[] = 'option=com_'.$this->getIdentifier()->package;
			}

			// Add the layout information to the route only if it's not 'default'
			if(!isset($parts['view']))
			{
				$result[] = 'view='.$this->getName();
				if(!isset($parts['layout']) && $this->_layout != $this->_layout_default) {
					$result[] = 'layout='.$this->getLayout();
				}
			}

			// Add the format information to the URL only if it's not 'html'
			if(!isset($parts['format']) && $this->getIdentifier()->name != 'html') {
				$result[] = 'format='.$this->getIdentifier()->name;
			}

			// Reconstruct the route
			if(!empty($route)) {
				$result[] = $route;
			}

			$result = 'index.php?'.implode('&', $result);

		}

		return JRoute::_($result);
	}

	/**
	 * Returns the views output
 	 *
	 * @return 	string
	 */
	public function __toString()
	{
		return $this->display();
	}
}