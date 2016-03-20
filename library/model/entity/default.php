<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Default Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Model
 */
final class ModelEntityDefault extends ModelEntityAbstract implements ObjectInstantiable
{
    /**
     * Create an entity or a collection instance
     *
     * @param  ObjectConfigInterface   $config	  A ObjectConfig object with configuration options
     * @param  ObjectManagerInterface	$manager  A ObjectInterface object
     * @return EventPublisher
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        $name = $config->object_identifier->name;

        if(StringInflector::isSingular($name)) {
            $class = 'Kodekit\Library\ModelEntityRow';
        } else {
            $class = 'Kodekit\Library\ModelEntityRowset';
        }

        $instance = new $class($config);
        return $instance;
    }
}