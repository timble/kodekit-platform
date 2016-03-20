<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Model Context
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Model
 */
class ModelContext extends Command implements ModelContextInterface
{
    /**
     * Constructor.
     *
     * @param  array|\Traversable  $attributes An associative array or a Traversable object instance
     */
    public function __construct($attributes = array())
    {
        ObjectConfig::__construct($attributes);

        //Set the subject and the name
        if($attributes instanceof ModelContext)
        {
            $this->setSubject($attributes->getSubject());
            $this->setName($attributes->getName());
        }
    }

    /**
     * Set the model state
     *
     * @param ModelState $state
     *
     * @return ModelContext
     */
    public function setState($state)
    {
        return ObjectConfig::set('state', $state);
    }

    /**
     * Get the model data
     *
     * @return array
     */
    public function getState()
    {
        return ObjectConfig::get('state');
    }

    /**
     * Set the model entity
     *
     * @param ModelEntityInterface $entity
     * @return ModelContext
     */
    public function setEntity($entity)
    {
        return ObjectConfig::set('entity', $entity);
    }

    /**
     * Get the model data
     *
     * @return array
     */
    public function getEntity()
    {
        return ObjectConfig::get('entity');
    }

    /**
     * Get the identity key
     *
     * @return mixed
     */
    public function getIdentityKey()
    {
        return ObjectConfig::get('identity_key');
    }

    /**
     * Set the identity key
     *
     * @param mixed $value
     * @return ModelContext
     */
    public function setIdentityKey($value)
    {
        return ObjectConfig::set('identity_key', $value);
    }
}