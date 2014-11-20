<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Model Context
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Model
 */
class ModelContext extends Command implements ModelContextInterface
{
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