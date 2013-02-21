<?php
/**
 * @package     Koowa_View
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * View Rss Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_View
 */
class KViewRss extends KViewTemplate
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
            'layout'   => 'rss',
            'template' => 'rss',
            'mimetype' => 'application/rss+xml',
            'data'     => array(
                'update_period'    => 'hourly',
                'update_frequency' => 1
            )
        ));

        parent::_initialize($config);
    }

	/**
	 * Return the views output
	 *
	 * This function will auto assign the model data to the view if the auto_assign
	 * property is set to TRUE.
 	 *
	 * @return string 	The output of the view
	 */
	public function display()
	{
	    $model = $this->getModel();

        //Auto-assign the state to the view
        $this->state = $model->getState();

        //Auto-assign the data from the model
        if($this->_auto_assign)
	    {
	        //Get the view name
		    $name  = $this->getName();

	        //Assign the data of the model to the view
		    if(KInflector::isPlural($name))
			{
		        $this->$name = $model->getRowset();
				$this->total = $model->getTotal();
		    }
			else $this->$name = $model->getRow();
		}

		return parent::display();
	}

    /**
     * Sets the layout name to use
     *
     * @param    string  The template name.
     * @return   KViewAbstract
     */
    public function setLayout($layout)
    {
        //Don't allow to change the layout
        return parent::setLayout($this->_layout);
    }
}