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
          
         $this->getTemplate()->addPath($path);
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
            'params'   => null,
			'module'   => null,
			'attribs'  => array(),
       	));
       	
       	parent::_initialize($config);
    }
    
	/**
	 * Get the identifier for the model with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getModel()
	{
		if(!$this->_model)
		{
			$identifier	= clone $this->_identifier;
			$identifier->name	= 'model';
			
			$this->_model = KFactory::get($identifier);
		}
       	
		return $this->_model;
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
			$identifier->name	= 'template';
			
			$this->_template = KFactory::get($identifier);
		}
		
		return $this->_template;
	}
	
	/**
	 * Renders and echo's the views output
 	 *
	 * @return ModDefaultHtml
	 */
	public function display()
	{
		//Render the template
		$template = $this->getTemplate()
				->loadIdentifier($this->_layout, $this->_data)
				->render(true);
		
		$document = KFactory::get('lib.joomla.document');
		
		foreach($this->getStyles() as $style) 
		{
			if($style['link']) {
				$document->addStyleSheet($style['data'], 'text/css', null, $style['attribs']);
			} else {
				$document->addStyleDeclaration($style['data']);
			}
		}
			
		foreach($this->getScripts() as $script) 
		{
			if($script['link']) {
				$document->addScript($script['data'], 'text/javascript');
			} else {
				$document->addScriptDeclaration($script['data']);
			}
		}
		
		echo $template;
		return $this;
	}
}