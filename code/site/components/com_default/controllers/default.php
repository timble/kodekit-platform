<?php
/**
 * @version     $Id: default.php 2721 2010-10-27 00:58:51Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Controller
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerDefault extends KControllerService
{
    /**
     * Display action
     * 
     * If the controller was not dispatched manually load the langauges files 
     *
     * @param   KCommandContext A command context object
     * @return  KDatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionGet(KCommandContext $context)
    {
        //Load the language file for HMVC requests who are not routed through the dispatcher
        if(!$this->isDispatched()) {
            JFactory::getLanguage()->load('com_'.$this->getIdentifier()->package); 
        }
        
        return parent::_actionGet($context);
    }
    
	/**
     * Browse action
     * 
     * Use the application default limit if no limit exists in the model and limit the
     * limit to a maximum of 100.
     *
     * @param   KCommandContext A command context object
     * @return  KDatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionBrowse(KCommandContext $context)
    {
        if($this->isDispatched()) 
        {
            $limit = $this->getModel()->get('limit');
            
            //If limit is empty use default
            if(empty($limit)) {
                $limit = JFactory::getApplication()->getCfg('list_limit');
            }

            //Limit cannot be larger then 100
            if($limit > 100) {
                $limit = 100;
            }
            
            $this->limit = $limit; 
        }
         
        return parent::_actionBrowse($context);
    }
    
	/**
     * Set a request property
     * 
     *  This function translates 'limitstart' to 'offset' for compatibility with Joomla
     *
     * @param  	string 	The property name.
     * @param 	mixed 	The property value.
     */
 	public function __set($property, $value)
    {          
        if($property == 'limitstart') {
            $property = 'offset';
        } 
        	
        parent::__set($property, $value);     
  	}
}