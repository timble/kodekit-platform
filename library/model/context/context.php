<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Model Context
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
        $this->set('state', $state);

        return $this;
    }

    /**
     * Get the model data
     *
     * @return array
     */
    public function getState()
    {
        return $this->get('state');
    }

    /**
     * Set the model entity
     *
     * @param ModelEntityInterface $entity
     * @return ModelContext
     */
    public function setEntity($entity)
    {
        $this->set('entity', $entity);

        return $this;
    }

    /**
     * Get the model data
     *
     * @return array
     */
    public function getEntity()
    {
        return $this->get('entity');
    }
}