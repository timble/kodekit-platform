<?php

class ComLanguagesModelComponents extends KModelTable
{
	/**
	 * Maps component names to table prefixes
	 *
	 * @var array
	 */
	protected $_list = array(
		'virtuemart' => 'vm',
		'menus' 	 => 'menu'
	);
	
	/**
	 * Finds the table prefix for a component
	 *
	 * @param 	string	Component name
	 * @return	string	Table prefix
	 */
	public function getTablePrefix($option)
	{
		$component = substr($option, 4);
		
		if('trash' == $component) {
			$prefix = strtolower(substr(JRequest::getCmd('task'), 4));
		} elseif(array_key_exists($component, $this->_list)) {
			$prefix =  $this->_list[$component];			
		} else {
			$prefix = $component;
		}
		
		return $prefix;
	}
	
	/**
	 * Is a component translatable?
	 *
	 * @param	string	Component name
	 * @param	string	View name
	 * @return 	bool
	 */
	public function isTranslatable($option, $view = '')
	{
		if(empty($option)) {
			return false;
		}
		
		$prefix = $this->getTablePrefix($option);
		if(!empty($view)) {
			$prefix = $prefix.'_'.$view;
		}
		
		$nooku  = $this->getService('com://admin/languages/model.tables');
		$tables = $nooku->getTranslatedTables();
		
		foreach($tables as $table)
		{
			if(strpos($table, $prefix) !== FALSE) {
				return true;
			}
		}
		
		return false;
	}
}