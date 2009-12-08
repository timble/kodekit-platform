<?php
/**
 * @version     $Id: koowa.php 1296 2009-10-24 00:15:45Z johan $
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Default Html View
.*
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */
class ComDefaultViewHtml extends KViewDefault
{
	/**
	 * Associatives array of view names
	 * 
	 * @var array
	 */
	protected $_views;
	
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
        
        $this->_views = $options['views'];
        
        //Add the template override path
        $parts = $this->_identifier->path;
        
        array_shift($parts);
        if(count($parts) > 1) 
		{
			$path    = KInflector::pluralize(array_shift($parts));
			$path   .= count($parts) ? DS.implode(DS, $parts) : '';
			$path   .= DS.strtolower($this->getName());	
		} 
		else $path  = strtolower($this->getName());
		       
        $template = KFactory::get('lib.koowa.application')->getTemplate();
        $path     = JPATH_THEMES.DS.$template.DS.'html'.DS.'com_'.$this->_identifier->package.DS.$path;
          
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
            'views' =>  array(),
        );

        return array_merge($defaults, $options);
    }
	
	/**
	 * Execute and echo's the views output
 	 *
	 * @return KViewDefault
	 */
	public function display()
	{
		//Render the toolbar
		$toolbar = KFactory::get($this->getToolbar());

		$this->_document->setBuffer($toolbar->render(), 'modules', 'toolbar');
		$this->_document->setBuffer($toolbar->renderTitle(), 'modules', 'title');
		
		//Render the submenu
		foreach($this->_views as $view => $title)
		{
			$active    = ($view == strtolower($this->getName()));
			$component = $this->_identifier->package;
			
			JSubMenuHelper::addEntry(JText::_($title), 'index.php?option=com_'.$component.'&view='.$view, $active );
		}

		return parent::display();
	}
	
	/**
	 * Get the identifier for the toolbar with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getToolbar()
	{
		$identifier			= clone $this->_identifier;
		$identifier->path	= array('toolbar');
		$identifier->name   = $this->getName();
		
		return $identifier;
	}
}