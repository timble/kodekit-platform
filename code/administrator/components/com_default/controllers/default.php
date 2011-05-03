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
class ComDefaultControllerDefault extends KControllerResource
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->registerCallback(array('after.edit', 'after.delete', 'after.add'), array($this, 'setMessage'));
    }
    
    public function  _initialize(KConfig $config) 
  	{        
		$config->append(array(
			'behaviors'	 =>  array('editable')
		));
	
      	parent::_initialize($config);
  	}
       
    /**
     * Filter that creates a redirect message based on the action
     * 
     * This function takes the row(set) status into account. If the status is STATUS_FAILED the status message information 
     * us used to generate an appropriate redirect message and set the redirect to the referrer. Otherwise, we generate the 
     * message based on the action and identifier name.
     *
     * @param KCommandContext   The active command context
     * @return void
     */
    public function setMessage(KCommandContext $context)
    { 
        $data = $context->result;
        
        if($status = $data->getStatus()) 
        {
            $message = $data->getStatusMessage();
            
            if(empty($message))
            {
                $name = ucfirst(KInflector::singularize($this->_identifier->name));
             
                if($status != KDatabase::STATUS_FAILED)
                {
                    $type    = 'message';
                    $message = JText::_($name. ' ' .$status);
                }
                else  
                {
                    $type    = 'error';
                    $message = JText::_($name. ' ' .$context->action.' failed');
                }
            } 

            $this->_redirect_type    = $type;
            $this->_redirect_message = $message;
        }  
    }
 
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