<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Identifier interface
 *
 * Wraps identifiers of the form type://package.[.path].name in an object, providing public accessors and methods for
 * derived formats.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
interface ObjectIdentifierInterface extends \Serializable
{
    /**
     * Get the identifier type
     *
     * @return string
     */
    public function getType();

    /**
     * Get the identifier package
     *
     * @return string
     */
    public function getPackage();

    /**
     * Get the identifier package
     *
     * @return array
     */
    public function getPath();

    /**
     * Get the identifier package
     *
     * @return string
     */
    public function getName();

    /**
     * Get the config
     *
     * @return ObjectConfig
     */
    public function getConfig();

    /**
     * Get the identifier class name
     *
     * @return string
     */
    public function getClass();

    /**
     * Set the identifier class name
     *
     * @param  string $class
     * @return ObjectIdentifierInterface
     */
    public function setClass($class);

    /**
     * Add a mixin
     *
     *  @param mixed $decorator An object implementing ObjectMixinInterface, an ObjectIdentifier or an identifier string
     * @param array $config     An array of configuration options
     * @return ObjectIdentifierInterface
     * @see Object::mixin()
     */
    public function addMixin($mixin, $config = array());

    /**
     * Get the mixins
     *
     *  @return array
     */
    public function getMixins();

    /**
     * Add a decorator
     *
     * @param mixed $decorator An object implementing ObjectDecoratorInterface, an ObjectIdentifier or an identifier string
     * @param array $config    An array of configuration options
     * @return ObjectIdentifierInterface
     * @see Object::decorate()
     */
    public function addDecorator($decorator, $config = array());

    /**
     * Get the decorators
     *
     *  @return array
     */
    public function getDecorators();

    /**
     * Formats the identifier as a [application::]type.component.[.path].name string
     *
     * @return string
     */
    public function toString();

    /**
     * Formats the identifier as an associative array
     *
     * @return array
     */
    public function toArray();
}