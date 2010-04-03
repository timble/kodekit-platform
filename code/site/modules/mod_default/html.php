<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Modules
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.koowa.org
*/

class ModDefaultHtml extends KViewHtml
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
        parent::__construct($config);
        
		//Assign module specific options
        $this->params  = $config->params;
        $this->module  = $config->module;
        $this->attribs = $config->attribs;
        
        $template = KFactory::get('lib.koowa.application')->getTemplate();
        $path     = JPATH_THEMES.DS.$template.DS.'html'.DS.'mod_'.$this->_identifier->package;
          
        $this->addTemplatePath($path);
	}
	
	/**
	 * Initializes the default configuration for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return  void
	 */
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
            'params'  => null,
			'module'  => null,
			'attribs' => array(),
       	));
       	
       	parent::_initialize($config);
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