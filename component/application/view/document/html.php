<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Html Document View
 *
 * @author      Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class ViewDocumentHtml extends ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'template_filters'	=> array('style', 'link', 'meta', 'script', 'title', 'message'),
        ));

        parent::_initialize($config);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        if($this->getObject('manager')->isRegistered('dispatcher')) {
            $context->data->component = $this->getObject('dispatcher')->getIdentifier()->package;
        } else {
            $context->data->component = '';
        }

        parent::_fetchData($context);
    }
}