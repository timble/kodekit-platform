<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Activities;

use Kodekit\Library;
use Kodekit\Component\Activities;

/**
 * Activity Model Entity.
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Activities
 */
class ModelEntityActivity extends Activities\ModelEntityActivity implements Library\ObjectInstantiable
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $data = $config->data;

        if ($data->package == Library\StringInflector::pluralize($data->name)) {
            $config->append(array('object_table' => $data->package));
        }

        parent::_initialize($config);
    }

    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        $class = $manager->getClass($config->object_identifier, false);

        if ($class == get_class())
        {
            $data = $config->data;

            $identifier = sprintf('com:%s.activity.%s', $data->package, $data->name);

            if ($manager->getClass($identifier, false)) {
                return $manager->getObject($identifier, $config->toArray());
            }
        }

        return new $class($config);
    }

    public function getPropertyImage()
    {
        $image = 'default';

        $images = array('add' => 'add', 'delete' => 'delete', 'edit' => 'edit');

        if (isset($images[$this->action])) {
            $image = $images[$this->action];
        }

        return $image;
    }
}