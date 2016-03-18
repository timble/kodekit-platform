<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Files;

use Nooku\Library;

/**
 * Directory Controller
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Files
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