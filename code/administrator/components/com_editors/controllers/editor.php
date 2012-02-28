<?php
class ComEditorsControllerEditor extends KControllerResource
{
	/**
	 * Supports a simple form Fluent Interfaces. Allows you to set the request 
	 * properties by using the request property name as the method name.
	 *
	 * For example : $controller->view('name')->limit(10)->browse();
	 *
	 * @param	string	Method name
	 * @param	array	Array containing all the arguments for the original call
	 * @return	KControllerBread
	 *
	 * @see http://martinfowler.com/bliki/FluentInterface.html
	 */
	public function __call($method, $args)
	{
	    //Check first if we are calling a mixed in method. 
	    //This prevents the model being loaded durig object instantiation. 
		if(!isset($this->_mixed_methods[$method]) && $method != 'display') 
        {
            //Check if the method is a state property
			$view = $this->getView();
			$view->$method($args[0]);
	
			return $this;
        }
		
		return parent::__call($method, $args);
	}
}