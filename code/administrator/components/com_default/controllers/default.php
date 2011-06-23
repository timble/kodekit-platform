<?php
/**
 * @version     $Id$
 * @category	Nooku
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
     * Read action
     *
     * This functions implements an extra check to hide the main menu is the view name
     * is singular (item views)
     *
     *  @return KDatabaseRow    A row object containing the selected row
     */
    protected function _actionRead(KCommandContext $context)
    {
        //Perform the read action
        $row = parent::_actionRead($context);
        
        //Add the notice if the row is locked
        if(isset($row))
        {
            if(!isset($this->_request->layout) && $row->isLockable() && $row->locked()) {
                KFactory::get('lib.joomla.application')->enqueueMessage($row->lockMessage(), 'notice');
            }
        }

        return $row;
    }
    
    /**
     * Display action
     * 
     * This function will load the language files of the component if the controller was
     * not dispatched. 
     *
     * @param   KCommandContext A command context object
     * @return  KDatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionGet(KCommandContext $context)
    {
        //Load the language file for HMVC requests who are not routed through the dispatcher
        if(!$this->isDispatched()) {
            KFactory::get('lib.joomla.language')->load('com_'.$this->getIdentifier()->package); 
        }
        
        return parent::_actionGet($context);
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