<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View JSON Class
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_View
 */
class KViewJson extends KViewAbstract
{
	/**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
			'mimetype'	  => 'application/json',
       	));
    	
    	parent::_initialize($config);
    }

	/**
	 * Return the views output
 	 *
	 *  @return string 	The output of the view
	 */
    public function display()
    {	
		$model = $this->getModel();
    	
    	//Get the view name
		$name = $this->getName();
			
		//Assign the data of the model to the view
		if(KInflector::isPlural($name)) {
			$data = $model->getList();
		} else {
			$data = $model->getItem();
		}
		
    	$this->output = json_encode($data->getData());
    	
    	return parent::display();
    }
}