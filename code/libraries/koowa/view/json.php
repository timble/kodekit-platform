<?php
/**
 * @version     $Id$
 * @package     Koowa_View
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
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
		if(empty($this->output)) {
			$this->output = KInflector::isPlural($this->getName()) ? $this->_getList() : $this->_getItem();
		}

		if (!is_string($this->output)) {
			$this->output = json_encode($this->output);
		}

		//Handle JSONP
		if(!empty($this->_padding)) {
			$this->output = $this->_padding.'('.$this->output.');';
		}

		return parent::display();
	}

	/**
	 * Get the list data
	 *
	 * @return array 	The array with data to be encoded to json
	 */
	protected function _getList()
	{
		$model = $this->getModel();

		$url   = clone KRequest::url();
		$state = $model->getState();
		
	    $vars = array();
	    foreach($state->toArray(false) as $var) 
	    {
	        if(!$var->unique) {
	            $vars[] = $var->name;
	        }  
	    }

		$data = array(
			'version'  => '1.0',
			'href'     => (string) $url->setQuery($state->toArray()),
			'url'      => array(
				'type'     => 'application/json',
				'template' => (string) $url->get(KHttpUrl::BASE).'?{&'.implode(',', $vars).'}',
			),
			'offset'   => (int) $model->offset,
			'limit'    => (int) $model->limit,
			'total'	   => 0,
			'items'    => array(),
		);

		if($list = $model->getList())
		{
			$data = array_merge($data, array(
				'total'    => $model->getTotal(),
				'items'    => array_values($list->toArray())
			 ));
		}

		return $data;
	}

	/**
	 * Get the item data
	 *
	 *  @return array 	The array with data to be encoded to json
	 */
	protected function _getItem()
	{
		$model = $this->getModel();
		
		$url   = clone KRequest::url();
		$state = $model->getState();
		
	    $vars = array();
	    foreach($state->toArray(false) as $var) 
	    {
	        if($var->unique) 
	        {
	            $vars[] = $var->name;
	            $vars   = array_merge($vars, $var->required);
	        }  
	    }
		
		$data = array(
			'version' => '1.0',
		    'href'    => (string) $url->setQuery($state->getData(true)),
	        'url'     => array(
				'type'     => 'application/json',
				'template' => (string) $url->get(KHttpUrl::BASE).'?{&'.implode(',', $vars).'}',
	        ),
	        'item'	  => array()
		);

		if($item = $model->getItem())
		{
		    $data = array_merge($data, array(
				'item' => $item->toArray()
			 ));
		};

		return $data;
	}
}