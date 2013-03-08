<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Editable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ComExtensionsControllerBehaviorEditable extends Framework\ControllerBehaviorEditable
{  
    public function __construct(Framework\Config $config)
    { 
        parent::__construct($config);
        
        $this->registerCallback('before.browse' , array($this, 'setReferrer'));
    }
    
	protected function _actionSave(Framework\CommandContext $context)
	{
		$entity = $context->getSubject()->execute('edit', $context);
	    
		$context->response->setRedirect($this->getReferrer($context));
		return $entity;
	}
    
	protected function _actionCancel(Framework\CommandContext $context)
	{
        $context->response->setRedirect($this->getReferrer($context));
		return;
	}

	protected function _actionApply(Framework\CommandContext $context)
	{
		$entity = $context->getSubject()->execute('edit', $context);

        $context->response->setRedirect($context->request->getUrl());
		return $entity;
	}
}