<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Plugins
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.koowa.org
*/

/**
 * Default Koowa plugin
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category   	Nooku
 * @package     Nooku_Plugins
 * @subpackage  System
 */
class plgKoowaDefault extends KEventHandler
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

		parent::__construct();
	}
		
	public function onDatabaseBeforeDispatch(ArrayObject $args) 
	{	
		die;
	}
}