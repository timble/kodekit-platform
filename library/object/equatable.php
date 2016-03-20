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
 * Object Equatable Interface
 *
 * Used to test if two objects are equal
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
 */
interface ObjectEquatable
{
    /**
     * The equality comparison should neither be done by referential equality nor by comparing object handles
     * (i.e. getHandle() === getHandle()).
     *
     * However, you do not need to compare every object attribute, but only those that are relevant for assessing
     * whether both objects are identical or not.
     *
     * @param ObjectInterface $object
     * @return Boolean
     */
    public function equals(ObjectInterface $user);
}