<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * System Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */
abstract class ComSettingsDatabaseRowAbstract extends KDatabaseRowAbstract
{
    /**
     * The setting name
     * 
     * @var string
     */
    protected $_name;
    
    /**
     * The setting path
     * 
     * @var string
     */
    protected $_path;
    
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config = null)
    {
         parent::__construct($config);
         
         $this->_name = $config->name;
         $this->_path = $config->path;
    }
    
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
             'new'    => false,
             'name'   => 'system',
             'path'	  => '',
        ));
        
        parent::_initialize($config);
    } 
    
    /**
     * The setting type
     * 
     * @return string 	The setting type
     */
    public function getType()
    {
        return $this->getIdentifier()->name;
    }
    
	/**
     * The setting path
     * 
     * @return string 	The setting path
     */
    public function getPath()
    {
        return $this->_path;
    }
    
	/**
     * The setting name
     * 
     * @return string 	The setting name
     */
    public function getName()
    {
        return $this->_name;
    }
    
	/**
     * Get a handle for this object
     *
     * This function returns an unique identifier for the object. This id can be used as
     * a hash key for storing objects or for identifying an object
     *
     * @return string A string that is unique
     */
    public function getHandle()
    {
        return $this->getName();
    }
    
 	/**
     * Settings can never be new
     * 
     * @return bool 
     */
    public function isNew()
    {
        return false;
    }
}