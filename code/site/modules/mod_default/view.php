<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Modules
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Module View
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Modules
 * @subpackage  Default
 */
class ModDefaultView extends KViewHtml
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        //Assign module specific options
        $this->params  = $config->params;
        $this->module  = $config->module;
        $this->attribs = $config->attribs;
        
        $template = KFactory::get('lib.joomla.application')->getTemplate();
        $path     = JPATH_THEMES.DS.$template.DS.'html'.DS.'mod_'.$this->_identifier->package;
          
         $this->getTemplate()->addPath($path);
    }
    
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {     
        $config->append(array(
            'params'      => null,
            'module'      => null,
            'attribs'     => array(),
            'auto_filter' => true
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
		return $this->_identifier->package;
	}
	  
    /**
     * Renders and echo's the views output
     *
     * @return ModDefaultHtml
     */
    public function display()
    {
        $this->output = $this->getTemplate()
                ->loadIdentifier($this->_layout, $this->_data)
                ->render($this->_auto_filter);
                
        return $this->output;
    }
}