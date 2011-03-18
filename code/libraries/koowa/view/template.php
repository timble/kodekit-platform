<?php
/**
 * @version     $Id: abstract.php 1815 2010-03-27 21:42:55Z johan $
 * @category    Koowa
 * @package     Koowa_View
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Abstract Template View Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_View
 * @uses        KMixinClass
 * @uses        KTemplate
 * @uses        KFactory
 */
abstract class KViewTemplate extends KViewAbstract
{
    /**
     * Layout name
     *
     * @var     string
     */
    protected $_layout;
    
    /**
     * Default Layout name
     *
     * @var     string
     */
    protected $_layout_default;

    /**
     * Template identifier (APP::com.COMPONENT.template.NAME)
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
     * Auto filter
     *
     * @var boolean
     */
    protected $_auto_filter;
    
    /**
     * The assigned data
     *
     * @var boolean
     */
    protected $_data;
    
    /**
     * The view scripts
     *
     * @var array
     */
    protected $_scripts = array();
    
    /**
     * The view styles
     *
     * @var array
     */
    protected $_styles = array();

    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        // set the auto assign state
        $this->_auto_assign = $config->auto_assign;
        
        // set the auto filter state
        $this->_auto_filter = $config->auto_filter;
        
        // set the default layout for the view
        $this->_layout_default = $config->layout_default;
        
         // user-defined escaping callback
        $this->setEscape($config->escape);
        
        // set the layout
        $this->setLayout($config->layout);
        
        // set the template object
        if(!empty($config->template)) {
            $this->setTemplate($config->template);
        }
            
        //Get the template object
        $template = $this->getTemplate()->setView($this);
        
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
            array('media://' => $config->media_url.'/'), KTemplateFilter::MODE_READ | KTemplateFilter::MODE_WRITE
        );
        
        //Add alias filter for base:// namespace
        $template->getFilter('alias')->append(
            array('base://' => $config->base_url.'/'), KTemplateFilter::MODE_READ | KTemplateFilter::MODE_WRITE
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
        $config->append(array(
            'escape'           => 'htmlspecialchars',
            'layout_default'   => 'default',
            'template'         => null,
            'template_filters' => array('shorttag', 'alias', 'variable', 'script', 'style', 'link'),
            'template_path'    => null,
            'auto_assign'      => true,
            'auto_filter'	   => false,
            'base_url'         => KRequest::base(),
            'media_url'        => KRequest::root().'/media',
        ))->append(array(
            'layout'            => $config->layout_default
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Set a view properties
     *
     * @param   string  The property name.
     * @param   mixed   The property value.
     */
    public function __set($property, $value)
    {
        $this->_data[$property] = $value;
    }
    
    /**
     * Get a view property
     *
     * @param   string  The property name.
     * @return  string  The property value.
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
     * Return the views output
     *
     * @param  boolean 	If TRUE apply write filters. Default FALSE.
     * @return string   The output of the view
     */
    public function display()
    {
        if(empty($this->output))
		{
            //Load the template object
            $this->output = $this->getTemplate()
                 ->loadIdentifier($this->_layout, $this->_data)
                 ->render($this->_auto_filter);
		}
                        
        return parent::display();
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
    public function setLayout($layout, $default = false)
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
     * Get the identifier for the template with the same name
     *
     * @return  KIdentifierInterface
     */
    public function getTemplate()
    {
        if(!$this->_template)
        {
            $identifier = clone $this->_identifier;
            $name = array_pop($identifier->path);
            $identifier->name   = $name;
            $identifier->path   = array('template');
            
            $this->_template = KFactory::get($identifier);
        }
        
        return $this->_template;
    }
    
    /**
     * Method to set a template object attached to the view
     *
     * @param   mixed   An object that implements KObjectIdentifiable, an object that 
     *                  implements KIndentifierInterface or valid identifier string
     * @throws  KDatabaseRowsetException    If the identifier is not a table identifier
     * @return  KViewAbstract
     */
    public function setTemplate($template)
    {
        if(!($template instanceof KTemplateAbstract))
        {
            $identifier = KFactory::identify($template);
        
            if($identifier->path[0] != 'template') {
                throw new KViewException('Identifier: '.$identifier.' is not a template identifier');
            }
        
            $this->_template = KFactory::get($identifier);
        } 
        else $this->_template = $template;
            
        return $this;
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
     * Supports a simple form of Fluent Interfaces. Allows you to assign variables to the view 
     * by using the variable name as the method name. If the method name is a setter method the 
     * setter will be called instead.
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
        if(count($args) == 1) 
        { 
            if(method_exists($this, 'set'.ucfirst($method))) { 
                return $this->{'set'.ucfirst($method)}($args[0]); 
            } else { 
                return $this->set($method, $args[0]); 
            } 
        } 
        
        return parent::__call($method, $args); 
    } 
}