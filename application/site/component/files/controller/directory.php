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

        return $request;
    }

    public function _actionRead(Library\CommandContext $context)
    {
        $entity = parent::_actionRead($context);

        $request = clone $this->getRequest();

        $page   = $this->getObject('application.pages')->getActive();
        $params = new JParameter($page->params);

        if ($request->getFormat() == 'html')
        {
            if ($params->get('limit') > 0)
            {
                $request->query->set('limit', (int) $params->get('limit'));
            }
        }

        $view = $this->getView();

        if ($view->getLayout() == 'gallery')
        {
            $request->query->set('types', array('image'));
        }

        $request->query->set('thumbnails', true);
        $request->query->set('sort', $params->get('sort'));
        $request->query->set('direction', $params->get('direction'));

        $identifier       = clone $this->getIdentifier();
        $identifier->name = 'file';
        $controller       = $this->getObject($identifier, array('request' => $request));

        $view->files = $controller->browse();
        $view->total = $controller->getModel()->getTotal();

        return $entity;
    }
}