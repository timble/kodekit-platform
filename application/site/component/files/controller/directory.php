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

/**
 * Directory Controller
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Files
 */
class ControllerDirectory extends Library\ControllerModel
{
    public function getRequest()
    {
        $request = parent::getRequest();

        // Force container.
        $request->query->set('container', 'files-files');

        if ($request->query->get('view', 'cmd') == 'directory')
        {
            $page = $this->getObject('pages')->getActive();

            $params = $page->getParams('page');
            if (isset($params->limit) && $params->limit > 0) {
                $request->query->set('limit', $params->limit);
            }
        }

        return $request;
    }
}