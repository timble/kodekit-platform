<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Dispatcher Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComExtensionsDispatcher extends ComDefaultDispatcher
{
	protected function _actionDispatch(KCommandContext $context)
	{
		if(KRequest::method() == KHttpRequest::GET)
		{
	        $view = KRequest::get('get.view', 'cmd', $this->_controller);

		    if($view == 'modules' && !KRequest::has('get.application'))
		    {
			    $url = clone(KRequest::url());
                $url->query['application']  = 'site';
           
                JFactory::getApplication()->redirect($url);
		    }
		}
	
		return parent::_actionDispatch($context);
	}
}