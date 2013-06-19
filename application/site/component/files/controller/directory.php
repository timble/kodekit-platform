<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Folder Controller Class
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package     Nooku_Components
 * @subpackage  Files
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

            if (isset($params->limit) && $params->limit > 0)
            {
                $request->query->set('limit', $params->limit);
            }
        }

        return $request;
    }
}