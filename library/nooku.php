<?php
/**
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link         http://www.nooku.org
*/

/**
 * Nooku class
 *
 * Loads classes and files, and provides metadata for Nooku such as version info
 *
 * @author  Johan Janssens <johan@nooku.org>
 */
class Nooku
{
    /**
     * Nooku version
     *
     * @var string
     */
    const VERSION = '13.1';

    /**
     * Path to Nooku libraries
     *
     * @var string
     */
    protected $_path;

 	/**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     *
     * @param  array  An optional array with configuration options.
     */
    final private function __construct($config = array())
    {
        //Initialize the path
        $this->_path = dirname(__FILE__);

        //Load the legacy functions
        require_once $this->_path.'/legacy.php';

        //Create the loader
        require_once $this->_path.'/class/loader.php';
        $loader = Nooku\Library\ClassLoader::getInstance($config);

        //Create the object manager
        $config['class_loader'] = $loader;
        Nooku\Library\ObjectManager::getInstance($config);
    }

	/**
     * Clone
     *
     * Prevent creating clones of this class
     */
    final private function __clone() { }

	/**
     * Singleton instance
     *
     * @param  array  An optional array with configuration options.
     * @return Nooku
     */
    final public static function getInstance($config = array())
    {
        static $instance;

        if ($instance === NULL) {
            $instance = new self($config);
        }

        return $instance;
    }

    /**
     * Get the version of the Koowa library
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Get path to Nooku libraries
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }
}