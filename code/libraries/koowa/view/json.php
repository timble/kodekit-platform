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
 * The JSON view implements supports for JSONP through the models callback
 * state. If a callback is present the output will be padded.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_View
 */
class KViewJson extends KViewAbstract
{
 	/**
     * The padding for JSONP
     *
     * @var string
     */
    protected $_padding;

 	/**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Padding can explicitly be turned off by setting to FALSE
        if(empty($config->padding) && $config->padding !== false)
        {
            $state = $this->getModel()->getState();

            if(isset($state->callback) && (strlen($state->callback) > 0)) {
                $config->padding = $state->callback;
            }
        }

        $this->_padding = $config->padding;
    }

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
    	    'padding'	  => ''
       	));

    	parent::_initialize($config);
    }

	/**
	 * Return the views output
	 *
	 * If the view 'output' variable is empty the output will be generated based on the
	 * model data, if it set it will be returned instead.
	 *
	 * If the model contains a callback state, the callback value will be used to apply
	 * padding to the JSON output.
 	 *
	 *  @return string 	The output of the view
	 */
    public function display()
    {
        if(empty($this->output))
        {
            $model = $this->getModel();

            if(KInflector::isPlural($this->getName())) 
            {
                if($list = $model->getList())  {   
                    $data = array_values($list->toArray());
                }    
            } 
            else 
            {
                if($item = $model->getItem()) {
                    $data = $item->toArray();
                }
            }

            $this->output = $data;
        }

        if (!is_string($this->output)) {
        	$this->output = json_encode($this->output);
        }

        //Handle JSONP
        if(!empty($this->_padding)) {
            $this->output     = $this->_padding.'('.$this->output.');';
        }

        return parent::display();
    }
}
