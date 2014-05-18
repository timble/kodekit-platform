<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Directory Controller
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Files
 */
class FilesControllerDirectory extends Library\ControllerModel
{
    public function getRequest()
    {
        $request = parent::getRequest();

        // Force container.
        $request->query->set('container', 'files-files');

        if ($request->query->get('view', 'cmd') == 'directory')
        {
            $page = $this->getObject('application.pages')->getActive();

            $params = new JParameter($page->params);
            if (isset($params->limit) && $params->limit > 0) {
                $request->query->set('limit', $params->limit);
            }
        }

        return $request;
    }
}