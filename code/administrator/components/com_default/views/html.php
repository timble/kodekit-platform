<?php
/**
 * @version     $Id: koowa.php 1296 2009-10-24 00:15:45Z johan $
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
	public $views;
	
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
        parent::__construct($config);
        
        $this->views = $config->views;
        
        //Add alias filter for editor helper
        $this->getTemplate()->getFilter('alias')->append(array(
        	'@editor(' => '$this->loadHelper(\'admin::com.default.template.helper.editor.display\', ')
        );
         
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
        $path     = JPATH_THEMES.'/'.$template.'/html/com_'.$this->_identifier->package.DS.$path;
          
        $this->getTemplate()->addPath($path);
	}
	
	/**
     * Initializes the configuration for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Configuration settings
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'views' 			=>  array(),
        ));
        
        parent::_initialize($config);
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
		
		return KFactory::get($identifier);
	}
}