<?php
/**
* @version		$Id$
* @category		Koowa
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link         http://www.nooku.org
*/

/**
 * Koowa constant, if true koowa is loaded
 */
define('KOOWA', 1);

/**
 * Koowa class
 *
 * Loads classes and files, and provides metadata for Koowa such as version info
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa
 */
class Koowa
{
    /**
     * Koowa version
     * 
     * @var string
     */
    const VERSION = '0.7.0-alpha-3';
    
    /**
     * Path to Koowa libraries
     * 
     * @var string
     */
    protected $_path;
      
 	/**
     * Constructor
     *
     * Prevent creating instances of this class by making the contructor private
     * 
     * @param  array  An optional array with configuration options.
     */
    final private function __construct($config = array()) 
    { 
        $this->_initialize($config);
    }
    
     /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array  An optional array with configuration options.
     * @return void
     */
    protected function _initialize($config = array())
    {
        //Initialize the path
        $this->_path = dirname(__FILE__);
        
        //Setup the loader
        require_once $this->_path.'/loader/loader.php';
        $loader = KLoader::getInstance();
        
        //Setup the factory
        $factory = KFactory::getInstance(); 
        $factory->set('koowa:loader', $loader);
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
     * @return Koowa
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
     * Get path to Koowa libraries
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }
}