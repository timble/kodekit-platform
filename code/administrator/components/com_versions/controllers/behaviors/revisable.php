<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Database Revisable Behavior
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Versions
 */
class ComVersionsControllerBehaviorRevisable extends KControllerBehaviorAbstract
{
	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'priority'	=> KCommand::PRIORITY_LOW,
        ));
 
        parent::_initialize($config);
    }
	
	/**
	 * Add toolbar commands
	 * .
	 * @param	KCommandContext	A command context object
	 */
    protected function _afterBrowse(KCommandContext $context)
    {    
        if($this->isCommandable())
        {
            $state = $this->getModel()->getState();
            
            if($state->trashed == true) 
            {    
                $this->getToolbar()->reset();
                 
                if($this->canEdit()) 
                {
                    $this->getToolbar()->addRestore(array(
                    	'attribs' => array('data-action' => 'edit')
                    ));
                }
            
                if($this->canDelete()) 
                {
                    $this->getToolbar()->addDelete(array(
                		'label' => 'Delete forever'
                     ));
                }
            }
        }
    }
}