<?php
/**
 * @version     $Id: component.php 1263 2009-10-15 00:20:35Z johan $
 * @category    Koowa
 * @package     Koowa_Loader
 * @subpackage  Adapter
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Abstract Loader Adapter
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Loader
 * @subpackage  Adapter
 * @uses        KIdentifier
 */
abstract class KLoaderAdapterAbstract implements KLoaderAdapterInterface
{
	/**
	 * The basepath 
	 * 
	 * @var string
	 */
	protected $_basepath = '';
	
	/**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options.
     */
    public function __construct( $basepath )
    {
        $this->_basepath = $basepath; 
    }
    
	/**
	 * Get the base path
	 *
	 * @return string	Returns the base path
	 */
	public function getBasepath()
	{
		return $this->_basepath;
	}
	
	/**
	 * Get the class prefix
	 *
	 * @return string	Returns the class prefix
	 */
	public function getPrefix()
	{
		return $this->_prefix;
	}
    
    /**
     * Get the path based on a class name or an identifier
     *
     * @param  string|object    The class name or an identifier -[application::]type.package.[.path].name
     * @return string|false     Returns the path on success FALSE on failure
     */
    public function path($identifier)
    {
        $path = false;
        
        if($identifier instanceof KIdentifierInterface) {
            $path = $this->_pathFromIdentifier($identifier);
        } else {
            $path = $this->_pathFromClassname($identifier);
        }
        
        return $path;
    }
    
    /**
     * Get the path based on an identifier
     *
     * @param  object           An Identifier object - [application::]type.package.[.path].name
     * @return string|false     Returns the path on success FALSE on failure
     */
    abstract protected function _pathFromIdentifier($identifier);
    
    /**
     * Get the path based on a class name
     *
     * @param  string           The class name 
     * @return string|false     Returns the path on success FALSE on failure
     */
    abstract protected function _pathFromClassname($classname); 
}