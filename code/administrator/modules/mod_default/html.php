<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.koowa.org
*/

class ModDefaultHtml extends KViewHtml
{
	/**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
        parent::__construct($options);
        
         // Initialize the options
		$options  = $this->_initialize($options);
        
		//Assign module specific options
        $this->params  = $options['params'];
        $this->module  = $options['module'];
        $this->attribs = $options['attribs'];
               
        $template = KFactory::get('lib.koowa.application')->getTemplate();
        $path     = JPATH_THEMES.DS.$template.DS.'html'.DS.'mod_'.$this->_identifier->package;
          
        $this->addTemplatePath($path);
	}
	
	/**
	 * Initializes the options for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param   array   Options
	 * @return  array   Options
	 */
	protected function _initialize(array $options)
	{
		$options = parent::_initialize($options);
		
		$defaults = array(
            'params'  => null,
			'module'  => null,
			'attribs' => array(),
       	);
       	
        return array_merge($defaults, $options);
    }
	
	/**
	 * Renders and echo's the views output
 	 *
	 * @return modDefaultHtml
	 */
	public function display()
	{
		//Render the template
		echo $this->loadTemplate();
		
		return $this;
	}
}