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
 * Autocomplete Template Helper
 *
 * @author		Stian Didriksen <stian@nooku.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelperAutocomplete extends KTemplateHelperBehavior
{
	/**
	 * Renders a text input with autocomplete behavior
	 *
	 * @see    KTemplateHelperBehavior::autocomplete
	 * @return string	The html output
	 */
	protected function _autocomplete($config = array())
 	{		
		$config = new KConfig($config);
		$config->append(array(
		    'model'		  => KInflector::pluralize($this->getIdentifier()->package),
		))->append(array(
			'validate'   => true,
			'identifier' => 'com://'.$this->getIdentifier()->application.'/'.$this->getIdentifier()->package.'.identifier.'.KInflector::pluralize($config->model)
		));
		
		if(!is_a($config->identifier, 'KServiceIdentifier')) {
		    $config->identifier = $this->getIdentifier($config->identifier);
		}
		
		$config->append(array(
		    'url'     => JRoute::_('&option=com_'.$config->identifier->package.'&view='.$config->identifier->name.'&format=json', false),
		    'column'  => KInflector::singularize($config->identifier->name).'_id'
		))->append(array(
		    'value'   => $config->{$config->column} ? $config->{$config->column} : '',
		    'attribs' => array(
		        'name'  => $config->column,
		        'type'  => 'text',
		        'class' => 'inputbox value',
		        'size'  => 60
		    )
		))->append(array(
		    'options' => array(
		        'valueField' => $config->attribs->name.'-value'
		    )
		))->append(array(
		    'attribs' => array(
		        'id' => $config->attribs->name,
		        'data-value' => $config->options->valueField,
		    )
		));
		
		if($config->validate) {
		    $config->attribs->class = $config->attribs->class.' ma-required';
		}

        //For the autocomplete behavior
    	$config->element = $config->attribs->id;

		$html  = $this->autocomplete($config);
		$html .= '<input '.KHelperArray::toString($config->attribs).' />';
	    $html .= '<input '.KHelperArray::toString(array(
            'type'  => 'hidden',
            'name'  => $config->attribs->name,
            'id'    => $config->options->valueField,
            'value' => $config->value
	       )).' />';

	    return $html;
 	}
 	
	/**
     * Search the mixin method map and call the method or trigger an error
     * 
     * This function check to see if the method exists in the mixing map if not
     * it will call the 'autocomplete' function. The method name will become the 'name'
     * in the config array.
     * 
     * This can be used to auto-magically create autocomplete select lists based on the 
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
            $config['model']  = KInflector::pluralize(strtolower($method));
            
            return $this->_autocomplete($config);
        }
        
        return parent::__call($method, $arguments);
    }
}