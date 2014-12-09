<?php
/**
 * Created by PhpStorm.
 * User: arunasmazeika
 * Date: 04/12/2014
 * Time: 16:36
 */

use Nooku\Library, Nooku\Component\Activities;

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
}