<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library, Nooku\Component\Activities;

/**
 * Activity Model Entity.
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ActivitiesModelEntityActivity extends Activities\ModelEntityActivity implements \Nooku\Library\ObjectInstantiable
{
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