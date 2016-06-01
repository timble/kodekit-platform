<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;
use Kodekit\Component\Files;

/**
 * Thumbnail Controller Class
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class ControllerThumbnail extends ControllerAbstract
{
    protected function _actionBrowse(Library\ControllerContext $context)
    {
        // Clone to make cacheable work since we change model states
        $model = clone $this->getModel();

        // Save state data for later
        $state_data = $model->getState()->getValues();

        $nodes = $this->getObject('com:files.model.nodes')->setState($state_data)->fetch();

        if (!$model->getState()->filename)
        {
            $needed = array();
            foreach ($nodes as $entity)
            {
                if ($entity instanceof Files\ModelEntityFile && $entity->isImage()) {
                    $needed[] = $entity->name;
                }
            }
        }
        else $needed = $model->getState()->filename;

        $model->setState($state_data)->filename($needed);
        $list = $model->fetch();

        $found = array();
        foreach ($list as $entity) {
            $found[] = $entity->filename;
        }

        if (count($found) !== count($needed))
        {
            $new = array();
            foreach ($nodes as $entity)
            {
                if ($entity instanceof Files\ModelEntityFile && $entity->isImage() && !in_array($entity->name, $found))
                {
                    $result = $entity->saveThumbnail();
                    if ($result) {
                        $new[] = $entity->name;
                    }
                }
            }

            if (count($new))
            {
                $model->getState()->setValues($state_data)->set('filename', $new);
                $additional = $model->fetch();

                foreach ($additional as $entity) {
                    $list->insert($entity);
                }
            }
        }

        return $list;
    }
}