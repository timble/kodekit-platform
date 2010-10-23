<?php
/**
* @version		$Id: default.php 2470 2010-08-20 22:16:48Z johanjanssens $
* @category		Koowa
* @package      Koowa_Plugins
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.koowa.org
*/

/**
 * Default Koowa plugin
 * 
 * Koowa plugins can handle a number of events that are dynamically generated. The following 
 * is a list of available events. This list is not meant to be exclusive.
 * 
 * onControllerBefore[Action]
 * onControllerAfter[Action]
 * where [Action] is Browse, Read, Edit, Add, Delete or any custom controller action
 * 
 * onDatabaseBefore[Action]
 * onDatabaseAfter[Action]
 * where [Action] is Select, Insert, Update or Delete
 * 
 * You can create your own Koowa plugins very easily :
 * 
 * <code>
 * <?php
 *  class plgKoowaFoo extends plgKoowaDefault
 * {
 * 		public function onControllerBeforeBrowse(KCommandcontext $context)
 * 		{
 * 			//The caller is a reference to the object that is triggering this event
 * 			$caller = $context->caller;
 * 
 * 			//The result is the actual result of the event, if this is an after event 
 * 			//the result will contain the result of the action.
 * 			$result = $context->result;
 * 
 * 			//The context object can also contain a number of custom properties
 *          print_r($context);
 * 		}	
 * }	
}
 * </code>
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category   	Koowa
 * @package     Koowa_Plugins
 * @subpackage  Koowa
 */
abstract class PlgKoowaDefault extends KEventListener
{	
	/**
	 * A JParameter object holding the parameters for the plugin
	 *
	 * @var	A JParameter object
	 */
	public $params	= null;

	/**
	 * The name of the plugin
	 *
	 * @var		string
	 */
	protected $_name = null;

	/**
	 * The plugin type
	 *
	 * @var		string
	 */
	protected $_type = null;
	
	/**
	 * Constructor
	 */
	function __construct($dispatcher, $config = array())
	{
		if ( isset( $config['params'] ) ) 
		{
			if(is_a($config['params'], 'JParameter')) {
				$this->params = $config['params'];
			} else {
				$this->params = new JParameter($config['params']);
			}
		}

		if ( isset( $config['name'] ) ) {
			$this->_name = $config['name'];
		}

		if ( isset( $config['type'] ) ) {
			$this->_type = $config['type'];
		}
		
		//Register the plugin with the dispatcher
		$dispatcher->register($this);
		
		//Force the identifier to NULL for now
		$config['identifier'] = null;

		parent::__construct(new KConfig($config));
	}
	
	/**
	 * Loads the plugin language file
	 *
	 * @param	string 	$extension 	The extension for which a language file should be loaded
	 * @param	string 	$basePath  	The basepath to use
	 * @return	boolean	True, if the file has successfully loaded.
	 */
	public function loadLanguage($extension = '', $basePath = JPATH_BASE)
	{
		if(empty($extension)) {
			$extension = 'plg_'.$this->_type.'_'.$this->_name;
		}

		return KFactory::get('lib.joomla.language')->load( strtolower($extension), $basePath);
	}
}