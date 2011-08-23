<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
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

class ComPagesDispatcher extends ComDefaultDispatcher
{
	protected function _actionDispatch(KCommandContext $context)
	{
		$view = KRequest::get('get.view', 'cmd', $this->_controller);

		if($view == 'pages' && !KRequest::has('get.menu'))
		{
			$page = KFactory::tmp('admin::com.pages.model.pages')->home(1)->getList()->top();
			
			$url = clone(KRequest::url());
            $url->query['view']   = $view;
            $url->query['menu']  = $page->pages_menu_id;
           
            KFactory::get('lib.joomla.application')->redirect($url);
		}
	
		return parent::_actionDispatch($context);
	}
}