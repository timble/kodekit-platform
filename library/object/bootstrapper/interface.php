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
 * Object Bootstrapper Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Bootstrapper
 */
interface ObjectBootstrapperInterface extends ObjectHandlable
{
    /**
     * Priority levels
     */
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH    = 2;
    const PRIORITY_NORMAL  = 3;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 5;

    /**
     * Perform the bootstrapping
     *
     * @return void
     */
    public function bootstrap();

    /**
     * Register components from a directory to be bootstrapped
     *
     * All the first level directories are assumed to be component folders and will be registered.
     *
     * @param string  $directory
     * @param bool    $bootstrap If TRUE bootstrap all the components in the directory. Default TRUE
     * @return ObjectBootstrapper
     */
    public function registerComponents($directory, $bootstrap = true);

    /**
     * Register a component to be bootstrapped.
     *
     * Class and object locators will be setup based on the information in the composer.json file.
     * If the component contains a /resources/config/bootstrapper.php file it will be registered.
     *
     * @param string $path          The component path
     * @param bool   $bootstrap     If TRUE bootstrap all the components in the directory. Default TRUE
     * @param array  $directories   Additional array of directories
     * @return ObjectBootstrapper
     */
    public function registerComponent($path, $bootstrap = true, array $directories = array());

    /**
     * Register a configuration file to be bootstrapped
     *
     * @param string $path  The absolute path to the file
     * @return ObjectBootstrapperInterface
     */
    public function registerFile($path);

    /**
     * Get the registered components
     *
     * @return array
     */
    public function getComponents();

    /**
     * Get a registered component path
     *
     * @param string $name    The component name
     * @param string $domain  The component domain. Domain is optional and can be NULL
     * @return string Returns the component path if the component is registered. FALSE otherwise
     */
    public function getComponentPath($name, $domain = null);

    /**
     * Get a hash based on a name and domain
     *
     * @param string $name    The component name
     * @param string $domain  The component domain. Domain is optional and can be NULL
     * @return string The hash
     */
    public function getComponentIdentifier($name, $domain = null);

    /**
     * Check if the bootstrapper has been run
     *
     * If you specify a specific component name the function will check if this component was bootstrapped.
     *
     * @param string $name    The component name
     * @param string $domain  The component domain. Domain is optional and can be NULL
     * @return bool TRUE if the bootstrapping has run FALSE otherwise
     */
    public function isBootstrapped($name = null, $domain = null);
}