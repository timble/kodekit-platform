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
 * Command Context Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Command
 */
interface CommandInterface
{
    /**
     * Get the event name
     *
     * @return string	The event name
     */
    public function getName();

    /**
     * Set the event name
     *
     * @param string $name The event name
     * @return CommandInterface
     */
    public function setName($name);

    /**
     * Get the command subject
     *
     * @return mixed The command subject
     */
    public function getSubject();

    /**
     * Set the command subject
     *
     * @param  mixed $subject The command subject
     * @return CommandInterface
     */
    public function setSubject($subject);

    /**
     * Set attributes
     *
     * Overwrites existing attributes
     *
     * @param  array|\Traversable $attributes
     * @throws \InvalidArgumentException If the attributes are not an array or are not traversable.
     * @return CommandInterface
     */
    public function setAttributes($attributes);

    /**
     * Get all arguments
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Get an attribute
     *
     * If the attribute does not exist, the $default value will be returned.
     *
     * @param  string $name The attribute name
     * @param  mixed $default
     * @return mixed
     */
    public function getAttribute($name, $default = null);

    /**
     * Set an attribute
     *
     * @param  string $name The attribute
     * @param  mixed $value
     * @return CommandInterface
     */
    public function setAttribute($name, $value);
}
