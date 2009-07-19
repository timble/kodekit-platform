<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Identifier
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Identifier for a component
 *
 * Wraps identifiers of the form application::extension.component.type[[.path].name]
 * in an object, providing public accessors and methods for derived formats
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Identifier
 */
class KFactoryIdentifierComponent extends KObject implements KFactoryIdentifierInterface
{
	/**
	 * The alias object map
	 *
	 * @var	array
	 */
	protected $_objectAliasMap = array(
      	'table'     => 'DatabaseTable',
        'row'       => 'DatabaseRow',
      	'rowset'    => 'DatabaseRowset'
	);


	/**
	 * The application name
	 *
	 * @var	string
	 */
	public $application;

	/**
	 * The extension [com|plg|lib|mod] type
	 * 
	 * @var string
	 */
	public $extension = '';

	/**
	 * The component name
	 *
	 * @var string
	 */
	public $component = '';

	/**
	 * The object type 	
	 * 
	 * @var string
	 */
	public $type = '';

	/**
	 * The path array
	 *
	 * @var array
	 */
	public $path = array();

	/**
	 * The object name
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * Constructor
	 *
	 * @param	string	Identifier or Identifier object - application::extension.component.type[[.path].name]
	 * @return 	void
	 */
	public function __construct($identifier)
	{
		// We also accept objects
		$identifier = (string) $identifier;
		
		// We only deal with foo::bar
		if(strpos($identifier, '::')) 
		{
			//Set the application name
			list($this->application, $parts) = explode('::', $identifier);

			//Explode the parts
			$parts = explode('.', $parts);

			// Set the extension
			$this->extension = array_shift($parts);

			// We only deal with components [com]
			if($this->extension == 'com') 
			{
				// Set the component
				$this->component = array_shift($parts);

				// Set the base type
				$this->type		= array_shift($parts);

				// Set the name (last part)
				if(count($parts)) {
					$this->name = array_pop($parts);
				}

				// Set the path (rest)
				if(count($parts)) {
					$this->path = $parts;
				}
			}
		}
	}

	/**
	 * Get the class name
	 *
	 * @return string
	 */
	public function getClassName()
	{
		$path =  KInflector::camelize(implode('_', $this->path));

        $classname = ucfirst($this->component).ucfirst($this->type).$path.ucfirst($this->name);
		return $classname;
	}
	
	/**
	 * Get the classname for the KFooBar or KFooDefault class
	 *
	 * @return string
	 */
	public function getDefaultClassName()
	{
		// convert 'table' to 'DatabaseTable' etc
		$alias = $this->type;
		
		if(array_key_exists($this->type, $this->_objectAliasMap)) {
			$alias = $this->_objectAliasMap[$this->type];
		}

		$path =  KInflector::camelize(implode('_', $this->path));

		if(class_exists( 'K'.ucfirst($alias).$path.ucfirst($this->name))) {
			$classname = 'K'.ucfirst($alias).$path.ucfirst($this->name);
		} else {
			$classname = 'K'.ucfirst($alias).$path.'Default';
		}
		return $classname;
	}

	/**
	 * Get the base path of the object
	 *
	 * @return string
	 */
	public function getFilePath()
	{
		// Admin is an alias for administrator
		$app  = ($this->application == 'admin') ? 'administrator' : $this->application;
		$path = JApplicationHelper::getClientInfo($app, true)->path.DS.'components'.DS.'com_'.$this->component;

		if(!empty($this->name))
		{
			$path .= DS.KInflector::pluralize($this->type);

			if(count($this->path))
			{
				foreach($this->path as $sub) {
					$path .= DS.KInflector::pluralize($sub);
				}
			}
			
			if($this->type == 'view') {
				$path .= DS.$this->name;
			}
		}
		
		return $path;
	}

	/**
	 * Get the filename
	 *
	 * @return string The file name for the class
	 */
	public function getFileName()
	{
		$filename = '';

		switch($this->type)
		{
			case 'view' :
			{
				//Get the document type
				$type     = KFactory::get('lib.joomla.document')->getType();
				$filename = $type.'.php';
			} break;

			default : $filename = strtolower($this->name).'.php';
		}

		return $filename;
	}

	/**
	 * Formats the indentifier as a string
	 *
	 * @return string
	 */
	public function __toString()
	{
		//Create the identifier string		
		$string = $this->application.'::'.$this->extension.'.'.$this->component.'.'.$this->type;

		if(count($this->path)) {
			$string .= '.'.implode('.',$this->path);
		}

		if(!empty($this->name)) {
			$string .= '.'.$this->name;
		}

		return $string;
	}
}