<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Model
 * @subpackage	Pagination
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Pagination Model
 *
 * To use, set the following states:
 * items.total:  		Total number of items
 * items.limit:  		Number of items per page
 * items.offset: 		The starting item for the current page
 * pages.display: 	Number of links to generate before and after the current offset,
 * 				or 0 for all (Optional)
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Model
 * @subpackage	Pagination
 */
class KModelPagination extends KObject
{
	/**
	 * A state object
	 *
	 * @var KRegistry object
	 */
	protected $_state;

	public function __construct()
	{
		$this->_state = new KObject();
	}

	/**
	 * Method to set model state variables
	 *
	 * @param	string	The name of the property
	 * @param	mixed	The value of the property to set
	 * @return	this
	 */
	public function setState( $property, $value = null )
	{
		$this->_state->set($property, $value);
		return $this;
	}

	/**
	 * Method to get model state variables
	 *
	 * @param	string	Optional parameter name
	 * @param   mixed	Optional default value
	 * @return	object	The property where specified, the state object where omitted
	 */
	public function getState($property = null, $default = null)
	{
		return $property === null ? $this->_state : $this->_state->get($property, $default);
	}

	/**
	 * Prepare some values
	 */
    public function prepare()
    {
    	$total	= (int) $this->getState('items.total');
		$limit	= (int) max($this->getState('items.limit', 20), 1);
		$offset	= (int) max($this->getState('items.offset'), 0);

		if($limit > $total) {
			$offset = 0;
		}
		if(!$limit) {
			$offset = 0;
			$limit  =  $total;
		}

		$pages_count	= (int) ceil($total / $limit);

    	if($offset > $total) {
			$offset = ($pages_count-1) * $limit;
		}

		$pages_current = (int) floor($offset / $limit) +1;

		$this->setState('items.total', $total)
			->setState('items.limit', $limit)
			->setState('items.offset', $offset)
			->setState('pages.count', $pages_count)
			->setState('pages.current', $pages_current);

		return $this;
    }



    /**
     * Get the offset for each page, optionally with a range
     *
     * @param	array $options
     * @return 	array	Page number => offset
     */
	protected function _getOffsets()
    {
   	 	if($display = $this->getState('pages.display'))
    	{
    		$start	= (int) max($this->getState('pages.current') - $display, 1);
    		$start	= min($this->getState('pages.count'), $start);
    		$stop	= (int) min($this->getState('pages.current') + $display, $this->getState('pages.count'));
    	}
    	else // show all pages
    	{
    		$start = 1;
    		$stop = $this->getState('pages.count');
    	}

    	$result = array();
    	foreach(range($start, $stop) as $pagenumber) {
    		$result[$pagenumber] = 	($pagenumber-1) * $this->getState('items.limit');
    	}

    	return $result;
    }


    public function getList()
    {
    	$this->prepare();
    	$elements = array();
    	$prototype = new KModelPaginationElement;
    	$current = ($this->getState('pages.current') - 1) * $this->getState('items.limit');

    	// First
    	$page = 1;
    	$offset = 0;
    	$active = $offset != $this->getState('items.offset');
    	$props = array('page' => $page, 'offset' => $offset, 'current' => false, 'active' => $active, 'text' => 'First');
    	$element 	= clone $prototype;
    	$elements[] = $element->setProperties($props);

    	// Previous
    	$page = $this->getState('pages.current') - 1;
    	$offset = max(
    				0,
    				($page - 1) * $this->getState('items.limit'));
		$active = $offset != $this->getState('items.offset');
    	$props = array('page' => $page, 'offset' => $offset, 'current' => false, 'active' => $active, 'text' => 'Previous');
    	$element 	= clone $prototype;
    	$elements[] = $element->setProperties($props);

		// Pages
		foreach($this->_getOffsets() as $page => $offset)
		{
			$current = $offset == $this->getState('items.offset');
			$props = array('page' => $page, 'offset' => $offset, 'current' => $current, 'active' => !$current, 'text' => $page);
    		$element 	= clone $prototype;
    		$elements[] = $element->setProperties($props);
		}


		// Next
    	$page = $this->getState('pages.current') + 1;
    	$offset = min(
    				($this->getState('pages.count')-1) * $this->getState('items.limit'),
    				($page - 1) * $this->getState('items.limit'));
 		$active = $offset != $this->getState('items.offset');
    	$props = array('page' => $page, 'offset' => $offset, 'current' => false, 'active' => $active, 'text' => 'Next');
    	$element 	= clone $prototype;
    	$elements[] = $element->setProperties($props);

    	// Last
    	$page = $this->getState('pages.count');
    	$offset = ($page - 1) * $this->getState('items.limit');
    	$active = $offset != $this->getState('items.offset');
    	$props = array('page' => $page, 'offset' => $offset, 'current' => false, 'active' => $active, 'text' => 'Last');
    	$element 	= clone $prototype;
    	$elements[] = $element->setProperties($props);

    	return $elements;
    }


}