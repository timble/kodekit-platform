<?php
/**
 * @version     $Id: default.php 2776 2011-01-01 17:08:00Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Plugins
 * @subpackage  Koowa
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
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
 * @author		Johan Janssens <johan@nooku.org>
 * @category   	Koowa
 * @package     Koowa_Plugins
 * @subpackage  Koowa
 */
abstract class PlgKoowaDefault extends KEventListener
{
	/**
	 * A JRegistry object holding the parameters for the plugin
	 *
	 * @var	A JRegistry object
	 */
	protected $_params	= null;

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
		if (isset($config['params']))
		{
		    if ($config['params'] instanceof JRegistry) {
				$this->_params = $config['params'];
			} else {
				$this->_params = new JRegistry;
				$this->_params->loadINI($config['params']);
			}
		}

		if ( isset( $config['name'] ) ) {
			$this->_name = $config['name'];
		}

		if ( isset( $config['type'] ) ) {
			$this->_type = $config['type'];
		}
		
		//Force the identifier to NULL for now
		$config['identifier'] = null;
		
		//Set the dispatcher
		$config['dispatcher'] = $dispatcher;

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

		return KFactory::get('joomla:language')->load( strtolower($extension), $basePath);
	}
}