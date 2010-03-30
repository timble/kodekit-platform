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
class KViewJson extends KViewTemplate
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		//Set the correct mime type
		$this->_document->setMimeEncoding('application/json');
	}

	/**
	 * Renders and echo's the views output
 	 *
	 * @return KViewJson
	 */
    public function display()
    {	
		$model = KFactory::get($this->getModel());
    	
    	//Get the view name
		$name = $this->getName();
			
		//Assign the data of the model to the view
		if(KInflector::isPlural($name)) {
			$data = $model->getList();
		} else {
			$data = $model->getItem();
		}
		
    	echo json_encode($data->getData());
    	
    	return $this;
    }
}