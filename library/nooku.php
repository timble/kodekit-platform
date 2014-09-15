<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

/**
 * Nooku Framework Loader
 *
 * Loads classes and files, and provides metadata for Nooku such as version info
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library
 */
class Nooku
{
    /**
     * Nooku version
     *
     * @var string
     */
    const VERSION = '1.0-alpha';

    /**
     * The root path
     *
     * @var string
     */
    protected $_root_path;

    /**
     * The base path
     *
     * @var string
     */
    protected $_base_path;

    /**
     * The vendor path
     *
     * @var string
     */
    protected $_vendor_path;

 	/**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     *
     * @param  array  $config An optional array with configuration options.
     */
    final private function __construct($config = array())
    {
        //Initialize the root path
        if(isset($config['root_path'])) {
            $this->_root_path = $config['root_path'];
        } else {
            $this->_root_path = realpath($_SERVER['DOCUMENT_ROOT']);
        }

        //Initialize the base path
        if(isset($config['base_path'])) {
            $this->_base_path = $config['base_path'];
        } else {
            $this->_base_path = $this->_root_path;
        }

        //Initialize the vendor path
        if(isset($config['vendor_path'])) {
            $this->_vendor_path = $config['vendor_path'];
        } else {
            $this->_vendor_path = $this->_root_path.'/vendor';
        }

        //Load the legacy functions
        require_once dirname(__FILE__).'/legacy.php';

        //Create the loader
        require_once dirname(__FILE__).'/class/loader.php';

        if (!isset($config['class_loader'])) {
            $config['class_loader'] = Nooku\Library\ClassLoader::getInstance($config);
        }

        //Create the object manager
        $manager = Nooku\Library\ObjectManager::getInstance($config);

        //Register the component class locator
        $manager->getClassLoader()->registerLocator(new Nooku\Library\ClassLocatorComponent(
            array(
                'namespaces' => array('\\' => $this->_base_path.'/component')
            )
        ));

        //Register the component object locator
        $manager->registerLocator('lib:object.locator.component');

        //Register the composer class locator
        if(file_exists($this->getVendorPath()))
        {
            $manager->getClassLoader()->registerLocator(new Nooku\Library\ClassLocatorComposer(
                array(
                    'vendor_path' => $this->getVendorPath()
                )
            ));
        }

        //Warm-up the stream factory
        $manager->getObject('lib:filesystem.stream.factory');
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
     * @param  array  $config An optional array with configuration options.
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
     * Get the framework version
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Get vendor path
     *
     * @return string
     */
    public function getVendorPath()
    {
        return $this->_vendor_path;
    }

    /**
     * Get root path
     *
     * @return string
     */
    public function getRootPath()
    {
        return $this->_root_path;
    }

    /**
     * Get base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->_base_path;
    }
}