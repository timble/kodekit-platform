<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Extensions;

use Nooku\Library;

/**
 * Editable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ControllerBehaviorEditable extends Library\ControllerBehaviorEditable
{  
    public function __construct(Library\ObjectConfig $config)
    { 
        parent::__construct($config);
        
        $this->registerCallback('before.browse' , array($this, 'setReferrer'));
    }

    public function canSave()
    {
        return $this->canEdit();
    }
    
	protected function _actionSave(Library\CommandContext $context)
	{
		$entity = $context->getSubject()->execute('edit', $context);
	    
		$context->response->setRedirect($this->getReferrer($context));
		return $entity;
	}
    
	protected function _actionCancel(Library\CommandContext $context)
	{
        $context->response->setRedirect($this->getReferrer($context));
		return;
	}

	protected function _actionApply(Library\CommandContext $context)
	{
		$entity = $context->getSubject()->execute('edit', $context);

        $context->response->setRedirect($context->request->getUrl());
		return $entity;
	}
}