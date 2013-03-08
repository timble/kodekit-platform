<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Files Html View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesViewFilesHtml extends ComBaseViewHtml
{
	protected function _initialize(Framework\Config $config)
	{
		$config->auto_assign = false;

		parent::_initialize($config);
	}

	public function render()
	{
	    $state = $this->getModel()->getState();
	    if (empty($state->limit)) {
	        $state->limit = $this->getService('application')->getCfg('list_limit');
	    }
	    
		$this->token     = $this->getService('user')->getSession()->getToken();
		$this->container = $this->getModel()->getState()->container;

		return parent::render();
	}
}
