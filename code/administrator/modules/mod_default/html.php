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
     * Constructor
     *
     * @param   array An optional associative array of configuration settings.
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
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'params'   => null,
            'module'   => null,
            'attribs'  =>  array(),
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Get the identifier for the model with the same name
     *
     * @return  KIdentifierInterface
     */
    public function getModel()
    {
        if(!$this->_model)
        {
            $identifier = clone $this->_identifier;
            $identifier->name   = 'model';
            
            $this->_model = KFactory::get($identifier);
        }
        
        return $this->_model;
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
            $identifier->name   = 'template';
            
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
        $this->output = $this->getTemplate()
                ->loadIdentifier($this->_layout, $this->_data)
                ->render(true);
                
        echo $this->output;
        return $this;
    }
}