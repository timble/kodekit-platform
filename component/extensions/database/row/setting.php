<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Extensions;

use Nooku\Library;

/**
 * Setting Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
abstract class DatabaseRowSetting extends Library\DatabaseRowAbstract
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
     * @param   object  An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
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
     * @param   object  An optional Library\ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
             'status' => Library\Database::STATUS_LOADED,
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