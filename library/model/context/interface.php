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
 * Model Context Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Model
 */
interface ModelContextInterface extends CommandInterface
{
    /**
     * Get the model state
     *
     * @return ModelState
     */
    public function getState();

    /**
     * Get the model entity
     *
     * @return ModelEntityInterface
     */
    public function getEntity();

    /**
     * Get the identity key
     *
     * @return mixed
     */
    public function getIdentityKey();
}