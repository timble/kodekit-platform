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
 * Default Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */

class ComSettingsControllerDefault extends ComDefaultControllerDefault
{
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'request' => array('view' => 'settings')
        ));
        
        parent::_initialize($config);
    }

	protected function _actionRead(KCommandContext $context)
	{
	    $name = ucfirst($this->getView()->getName());
	    	
		if(!$this->getModel()->getState()->isUnique()) {
		    $context->setError(new KControllerException($name.' Not Found', KHttpResponse::NOT_FOUND));
		} 
		
		return parent::_actionRead($context);
	}
}