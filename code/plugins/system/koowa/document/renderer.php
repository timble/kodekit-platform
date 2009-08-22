<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Abstract class for a renderer
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Document
 */
abstract class KDocumentRenderer extends KObject
{
	/**
	* reference to the KDocument object that instantiated the renderer
	*
	* @var		object
	*/
	protected $_doc = null;

	/**
	* Class constructor
	*
	* @param object A reference to the KDocument object that instantiated the renderer
	*/
	public function __construct(array $options = array()) 
	{
		if (array_key_exists('document', $options)) {
			$this->_doc = $options['document'];
		}
	}
	
	/**
	 * Renders a script and returns the results as a string
	 *
	 * @param string 	$name		The name of the element to render
	 * @param array 	$array		Array of values
	 * @param string 	$content	Override the output of the renderer
	 * @return string	The output of the script
	 */
	abstract public function render( $name, array $params = array(), $content = null );
}