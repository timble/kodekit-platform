<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPL <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.koowa.org
*/

/**
 * String filter
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterString extends KFilterHtml
{
	/**
	 * Constructor
	 *
	 * @param	array	Options array
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		
		$this->_tagsMethod = false;
		$this->_attrMethod = false;
	}
}

