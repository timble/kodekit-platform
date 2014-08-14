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
 * Thumbnailable Controller Behavior
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ControllerBehaviorThumbnailable extends Library\ControllerBehaviorAbstract
{
    protected function _afterBrowse(Library\ControllerContextInterface $context)
    {
        $container = $this->getModel()->getContainer();

        if ($context->request->query->get('thumbnails', 'cmd') || $container->getParameters()->thumbnails == true)
        {
            $files = array();
            foreach ($context->result as $entity)
            {
                if ($entity->getIdentifier()->name === 'file' && $entity->isImage()) {
                    $files[] = $entity->name;
                }
            }

            $thumbnails = $this->getObject('com:files.controller.thumbnail')
                ->container($this->getModel()->getState()->container)
                ->folder($this->getRequest()->query->folder)
                ->filename($files)
                ->limit(0)
                ->offset(0)
                ->browse();

            foreach ($context->result as $entity)
            {
                if ($thumbnail = $thumbnails->find(array('filename' => $entity->name))) {
                    $entity->thumbnail = $thumbnail->thumbnail;
                } else {
                    $entity->thumbnail = null;
                }
            }
        }
    }
}
