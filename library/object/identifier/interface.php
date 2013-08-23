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
     * Set the identifier type
     *
     * @param  string $type
     * @return  ObjectIdentifierInterface
     * @throws \DomainException If the type is unknown
     */
    public function setType($type);

    /**
     * Get the identifier package
     *
     * @return string
     */
    public function getPackage();

    /**
     * Set the identifier package
     *
     * @param  string $package
     * @return  ObjectIdentifierInterface
     */
    public function setPackage($package);

    /**
     * Get the identifier package
     *
     * @return array
     */
    public function getPath();

    /**
     * Set the identifier path
     *
     * @param  string $path
     * @return  ObjectIdentifierInterface
     */
    public function setPath(array $path);

    /**
     * Get the identifier package
     *
     * @return string
     */
    public function getName();

    /**
     * Set the identifier name
     *
     * @param  string $name
     * @return  ObjectIdentifierInterface
     */
    public function setName($name);

    /**
     * Get the config
     *
     * @return ObjectConfig
     */
    public function getConfig();

    /**
     * Set the config
     *
     * @param  ObjectConfig|array $data   A ObjectConfig object or a an array of configuration options
     * @param   boolean           $merge  If TRUE the data in $config will be merged instead of replaced. Default TRUE.
     * @return  ObjectIdentifierInterface
     */
    public function setConfig($data, $merge = true);

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
     * Add a object locator
     *
     * @param ObjectLocatorInterface $locator
     * @return ObjectIdentifierInterface
     */
    public static function addLocator(ObjectLocatorInterface $locator);

    /**
     * Get the object locator
     *
     * @return ObjectLocatorInterface|null  Returns the object locator or NULL if the locator can not be found.
     */
    public function getLocator();

    /**
     * Get the decorators
     *
     *  @return array
     */
    public static function getLocators();

    /**
     * Get the identifier class name
     *
     * @return string
     */
    public function getClassName();

    /**
     * Get the identifier file path
     *
     * @return string
     */
    public function getClassPath();

    /**
     * Check if the object is a singleton
     *
     * @return boolean Returns TRUE if the object is a multiton, FALSE otherwise.
     */
    public function isMultiton();

    /**
     * Check if the object is a singleton
     *
     * @return boolean Returns TRUE if the object is a singleton, FALSE otherwise.
     */
    public function isSingleton();

    /**
     * Formats the identifier as a [application::]type.component.[.path].name string
     *
     * @return string
     */
    public function toString();
}