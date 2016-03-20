<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Files;

use Kodekit\Library;
use Kodekit\Component\Files;

/**
 * Files Html View
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Platform\Files
 */
class ViewFilesHtml extends Files\ViewFilesHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $base = clone $this->getObject('request')->getBaseUrl();

        $context->data->sitebase = (string) $base;

        // FIXME: take out the hardcoded Itemid
        $base->setQuery(array('component' => 'files', 'Itemid' => 56));
        $this->getObject('application')->getRouter()->build($base);

        $context->data->base = (string) $base;

        parent::_fetchData($context);
    }
}