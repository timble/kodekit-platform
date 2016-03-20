<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Files Html View
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
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
