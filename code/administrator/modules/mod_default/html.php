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
class ModDefaultHtml extends KViewHtml
{
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
        $template = clone $this->getIdentifier();
        $template->name = 'template';
        
        $config->append(array(
            'template' 		   => $template,
        	'template_filters' => array('mod://admin/default.chrome'),
            'data'			   => array(
                'styles' => array() 
            )
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
		//Load the language files.
		//Type only exists if the module is loaded through ComExtensionsModelsModules
		if(isset($this->module->type)) {
            KFactory::get('joomla:language')->load($this->module->type);
		}
        
		if(empty($this->module->content)) 
		{
            $this->output = $this->getTemplate()
                ->loadIdentifier($this->_layout, $this->_data)
                ->render();
		}
		else 
		{
		     $this->output = $this->getTemplate()
                ->loadString($this->module->content, $this->_data, false)
                ->render();
		}
	
        return $this->output;
    }
    
    /**
     * Set a view properties
     *
     * @param   string  The property name.
     * @param   mixed   The property value.
     */
    public function __set($property, $value)
    {
        if($property == 'module') 
        {
            if(is_string($value->params)) {
                $value->params = new JParameter($value->params);
            }
        }
        
        parent::__set($property, $value);
    }
}