<?php
/**
 * @version		$Id: abstract.php 1815 2010-03-27 21:42:55Z johan $
 * @category	Koowa
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Template View Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 * @uses		KMixinClass
 * @uses 		KTemplate
 * @uses 		KFactory
 */
abstract class KViewTemplate extends KViewAbstract
{
	/**
	 * Layout name
	 *
	 * @var		string
	 */
	protected $_layout = 'default';

	/**
	 * Template identifier (APP::com.COMPONENT.template.NAME)
	 *
	 * @var	string|object
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
	 * The view scripts
	 *
	 * @var	array
	 */
	protected $_scripts = array();
	
	/**
	 * The view styles
	 *
	 * @var	array
	 */
	protected $_styles = array();

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		// set the auto assign state
		$this->_auto_assign = $config->auto_assign;
		
		 // user-defined escaping callback
        $this->setEscape($config->escape);
        
		// set the layout
		$this->setLayout($config->layout);
		
		// set the template object
		if(!empty($config->template)) {
			$this->setTemplate($config->template);
		}
			
		//Get the template object
		$template = KFactory::get($this->getTemplate(),  array('view' => $this));
		
		//Set the template filters
		if(!empty($config->template_filters)) {
			$template->addFilters($config->template_filters);
		}
		
		// Add default template paths
		if(!empty($config->template_path)) {
			$template->addPath($config->template_path);
		}
		
		// Set base and media urls for use by the view
		$this->assign('baseurl' , $config->base_url)
			 ->assign('mediaurl', $config->media_url);
		
		//Add alias filter for media:// namespace
        $template->getFilter('alias')->append(
        	array('media://' => $config->media_url.'/'), KTemplateFilter::MODE_WRITE
        );
		
        //Add alias filter for base:// namespace
        $template->getFilter('alias')->append(
        	array('base://' => $config->base_url.'/'), KTemplateFilter::MODE_WRITE
        );
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
            'escape'           => 'htmlspecialchars',
            'layout'           => 'default',
    		'template'		   => null,
			'template_filters' => array('shorttag', 'alias', 'variable', 'style', 'script'),
            'template_path'    => null,
			'auto_assign'	   => true,
    		'base_url'         => KRequest::base(),
        	'media_url'		   => KRequest::root().'/media',
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Set a view properties
     *
     * @param  	string 	The property name.
     * @param 	mixed 	The property value.
     */
 	public function __set($property, $value)
    {
    	$this->_data[$property] = $value;
  	}
  	
  	/**
     * Get a view property
     *
     * @param  	string 	The property name.
     * @return 	string 	The property value.
     */
    public function __get($property)
    {
    	$result = null;
    	if(isset($this->_data[$property])) {
    		$result = $this->_data[$property];
    	} 
    	
    	return $result;
    }

	/**
	* Assigns variables to the view script via differing strategies.
	*
	* This method is overloaded; you can assign all the properties of
	* an object, an associative array, or a single value by name.
	*
	* You are not allowed to set variables that begin with an underscore;
	* these are either private properties for KView or private variables
	* within the template script itself.
	*
	* <code>
	* $view = new KViewDefault();
	*
	* // assign directly
	* $view->var1 = 'something';
	* $view->var2 = 'else';
	*
	* // assign by name and value
	* $view->assign('var1', 'something');
	* $view->assign('var2', 'else');
	*
	* // assign by assoc-array
	* $ary = array('var1' => 'something', 'var2' => 'else');
	* $view->assign($obj);
	*
	* // assign by object
	* $obj = new stdClass;
	* $obj->var1 = 'something';
	* $obj->var2 = 'else';
	* $view->assign($obj);
	*
	* </code>
	*
	* @return KViewAbstract
	*/
	public function assign()
	{
		// get the arguments; there may be 1 or 2.
		$arg0 = @func_get_arg(0);
		$arg1 = @func_get_arg(1);

		// assign by object or array
		if (is_object($arg0) || is_array($arg0)) {
			$this->set($arg0);
		} 
		
		// assign by string name and mixed value.
		elseif (is_string($arg0) && substr($arg0, 0, 1) != '_' && func_num_args() > 1) {
			$this->set($arg0, $arg1);
		}

		return $this;
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
	* @param	string 	The template name.
	* @return 	KViewAbstract
	*/
	public function setLayout($layout)
	{
		$this->_layout = $layout;
		return $this;
	}

	 /**
     * Sets the _escape() callback.
     *
     * @param 	mixed The callback for _escape() to use.
     * @return 	KViewAbstract
     */
    public function setEscape($spec)
    {
        $this->_escape = $spec;
        return $this;
    }
    
	/**
	 * Get the identifier for the template with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getTemplate()
	{
		if(!$this->_template)
		{
			$identifier	= clone $this->_identifier;
			$name = array_pop($identifier->path);
			$identifier->name	= $name;
			$identifier->path	= array('template');
			
			$this->_template = $identifier;
		}
		
		return $this->_template;
	}
	
	/**
	 * Method to set a template object attached to the view
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a table identifier
	 * @return	KViewAbstract
	 */
	public function setTemplate($template)
	{
		$identifier = KFactory::identify($template);
		
		if($identifier->path[0] != 'template') {
			throw new KViewException('Identifier: '.$identifier.' is not a template identifier');
		}
		
		$this->_template = $identifier;
		return $this;
	}
	
	/**
	 * Add a style information
	 * 
	 * @param string	The style information
	 * @param boolean	True, if the style information is a URL
	 * @param array		Associative array of attributes
	 * @return KViewTemplate 
	 */
	public function addStyle($data, $link = true, array $attribs = array())
	{
		$signature = md5($data);
		$this->_styles[$signature] = array('data' => $data, 'link' => $link, 'attribs' => $attribs);
		return $this;
	}
	
	/**
	 * Get the style information 
	 * 
	 * This function return an associative array with 'data', 'link' and 
	 * 'attribs' keys. If the 'link' value is TRUE the data is a URL.
	 *
	 * @return array
	 */
	public function getStyles()
	{
		return $this->_styles;
	}
	
	/**
	 * Add a script information
	 * 
	 * @param string	The script information
	 * @param boolean	True, if the script information is a URL.
	 * @param array		Associative array of attributes
	 * @return KViewTemplate 
	 */
	public function addScript($data, $link = true, array $attribs = array())
	{
		$signature = md5($data);
		$this->_scripts[$signature] = array('data' => $data, 'link' => $link, 'attribs' => $attribs);
		return $this;
	}
	
	/**
	 * Get the script information 
	 * 
	 * This function return an associative array with 'data', 'link' and 
	 * 'attribs' keys. If the 'link' value is TRUE the data is a URL.
	 *
	 * @return array
	 */
	public function getScripts()
	{
		return $this->_scripts;
	}

	/**
	 * Load a template file -- first look in the templates folder for an override
	 * 
	 * This functions accepts both template local template file names or identifiers
	 * - application::com.component.view.[.path].name
	 *
	 * @param 	string 	The name of the template source file automatically searches
	 * 					the template paths and compiles as needed.
	 * @param 	array	An associative array of data to be extracted in local template scope
	 * @throws KViewException
	 * @return string The output of the the template script.
	 */
	public function loadTemplate( $identifier = null, $data = null)
	{
		// Clear prior output
		$this->output = '';
		
		// If no identifier has been specified using the view layout
		$identifier = isset($identifier) ? $identifier : $this->_layout;
		
		try
		{
			$identifier = new KIdentifier($identifier);
			
			$file = $identifier->name;
			$path = dirname(KLoader::path($identifier)).'/tmpl';
		} 
		catch(KIdentifierException $e) 
		{
			$file = $identifier;
			$path = dirname($this->_identifier->filepath).'/tmpl';
		}
			
		//Add the view to the data to allow accessing the view from inside the template
		$data = isset($data) ? $data : $this->_data;
		
		$result = KFactory::get($this->getTemplate())
					->find($path.'/'.$file.'.php', $data);
		
		return $result;  
	}
	
	/**
	 * Execute and return the views output
 	 *
	 * @return 	string
	 */
	public function __toString()
	{
		return $this->loadTemplate();
	}
}