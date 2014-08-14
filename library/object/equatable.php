<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Equatable Interface
 *
 * Used to test if two objects are equal
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
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