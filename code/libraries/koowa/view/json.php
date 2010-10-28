<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * View JSON Class
 *
 * @author      Johan Janssens <johan@nooku.org>
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