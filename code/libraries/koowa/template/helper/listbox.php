<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Template Listbox Helper
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelperListbox extends KTemplateHelperSelect
{
	/**
	 * Generates an HTML optionlist based on the distinct data from a model column.
	 * 
	 * The column used will be defined by the name -> value => column options in
	 * cascading order. 
	 * 
	 * If no 'model' name is specified the model identifier will be created using 
	 * the helper identifier. The model name will be the pluralised package name. 
	 * 
	 * If no 'value' option is specified the 'name' option will be used instead. 
	 * If no 'text'  option is specified the 'value' option will be used instead.
	 * 
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 * @see __call()
	 */
	protected function _listbox($config = array())
 	{
		$config = new KConfig($config);
		$config->append(array(
			'name'		  => '',
			'filter' 	  => array(),
			'attribs'	  => array(),
			'model'		  => KInflector::pluralize($this->getIdentifier()->package),
		    'prompt'      => '- Select -', 
		    'unique'	  => true
		))->append(array(
			'value'		 => $config->name,
			'selected'   => $config->{$config->name},
		    'identifier' => 'com://'.$this->getIdentifier()->application.'/'.$this->getIdentifier()->package.'.model.'.KInflector::pluralize($config->model)
		))->append(array(
			'text'		=> $config->value,
			'column'    => $config->value,
			'deselect'  => true,
		))->append(array(
		    'sort'	    => $config->text,
		));
		
		$list = KFactory::get($config->identifier)->limit(0)->set($config->filter)->sort($config->sort)->getList();
		
		//Get the list of items
 	    $items = $list->getColumn($config->value);
		if($config->unique) {
		    $items = array_unique($items);
		}

		//Compose the options array
        $options   = array();
 		if($config->deselect) {
         	$options[] = $this->option(array('text' => JText::_($config->prompt)));
        }
		
 		foreach($items as $key => $value) 
 		{
 		    $item      = $list->find($key);
 		    $options[] =  $this->option(array('text' => $item->{$config->text}, 'value' => $item->{$config->value}));
		}
		
		//Add the options to the config object
		$config->options = $options;

		return $this->optionlist($config);
 	}
 	
	/**
     * Search the mixin method map and call the method or trigger an error
     * 
     * This function check to see if the method exists in the mixing map if not
     * it will call the 'listbox' function. The method name will become the 'name'
     * in the config array.
     * 
     * This can be used to auto-magically create select filters based on the 
     * function name.
     *
     * @param  string   The function name
     * @param  array    The function arguments
     * @throws BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, array $arguments)
    {   
        if(!in_array($method, $this->getMethods())) 
        {
            $config = $arguments[0];
            $config['name']  = KInflector::singularize(strtolower($method));
            
            return $this->_listbox($config);
        }
        
        return parent::__call($method, $arguments);
    }
}