<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Files Html View
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */

class ViewFilesHtml extends Library\ViewHtml
{
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->auto_fetch = false;

		parent::_initialize($config);
	}

    protected function _fetchData(Library\ViewContext $context)
	{
	    $state = $this->getModel()->getState();

        $context->data->container = $this->getModel()->getContainer();
        $context->data->site      = $this->getObject('application')->getSite();
		$context->data->token     = $this->getObject('user')->getSession()->getToken();

		parent::_fetchData($context);
	}
}
