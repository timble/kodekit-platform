<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Editable Controller Behavior
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */
class ComSettingsControllerBehaviorEditable extends KControllerBehaviorEditable
{  
    public function __construct(KConfig $config)
    { 
        parent::__construct($config);
        
        $this->registerCallback('before.browse' , array($this, 'setReferrer'));
    }
    
	protected function _actionSave(KCommandContext $context)
	{
		$data = $context->caller->execute('edit', $context);
	    
		$this->setRedirect($this->getReferrer());
		
		return $data;
	}
    
	protected function _actionCancel(KCommandContext $context)
	{
		$this->setRedirect($this->getReferrer());
	
		return $data;
	}

	protected function _actionApply(KCommandContext $context)
	{
		$data = $context->caller->execute('edit', $context);
		
		$url  = clone KRequest::url();
		$this->setRedirect($url);
		
		return $data;
	}
}