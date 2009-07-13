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
	 * Application
	 *
	 * @var	string
	 */
	public $application;

	/**
	 * Extension [com|plg|lib|mod]
	 *
	 * @var string
	 */
	public $extension = '';

	/**
	 * Component name
	 *
	 * @var string
	 */
	public $component = '';

	/**
	 * Type name
	 *
	 * @var string
	 */
	public $type = '';

	/**
	 * Path
	 *
	 * @var array
	 */
	public $path = array();

	/**
	 * Name / suffix
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
		// we also accept objects
		$identifier = (string) $identifier;

		// we only deal with foo::bar
		if(strpos($identifier, '::') === false) {
			return;
		}

		list($this->application, $parts) = explode('::', $identifier);

		$parts 			= explode('.', $parts);

		// set the extension
		$this->extension = array_shift($parts);

		// we only deal with components
		if(!$this->extension == 'com') {
			return;
		}

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

	public function __toString()
	{
		$string = $this->application.'::'.$this->extension.'.'.$this->component.'.'.$this->type;

		if(count($this->path)) {
			$string .= '.'.implode('.',$this->path);
		}

		if(!empty($this->name)) {
			$string .= '.'.$this->name;
		}

		return $string;
	}

	public function getClassName()
	{
		$path =  KInflector::camelize(implode('_', $this->path));

        $classname = ucfirst($this->component).ucfirst($this->type).$path.ucfirst($this->name);
		return $classname;
	}

	public function getBasePath()
	{
		// Admin is an alias for administrator
		$app = ($this->application == 'admin') ? 'administrator' : $this->application;

		$base_path  = JApplicationHelper::getClientInfo($app, true)->path
						.DS.'components'.DS.'com_'.$this->component;

		if(!empty($this->name))
		{
			$base_path .= DS.KInflector::pluralize($this->type);

			if(count($this->path))
			{
				foreach($this->path as $sub) {
					$base_path .= DS.KInflector::pluralize($sub);
				}
			}
		}

		return $base_path;
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
				$type   = KFactory::get('lib.joomla.document')->getType();
				$filename = strtolower($this->name).DS.$type.'.php';
			} break;

			default : $filename = strtolower($this->name).'.php';
		}

		return $filename;
	}


	/**
	 * Get the classname for the KFooBar or KFooDefault class
	 *
	 * @return string
	 */
	public function getDefaultClass()
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
}