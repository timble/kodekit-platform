<?php
/**
 * @version     $Id: raw.php 427 2008-09-10 23:54:09Z Johan $
 * @category	Koowa
 * @package     Koowa_Document
 * @copyright   Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Provides a default implementation of KDocument
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Document
 */
class KDocumentDefault extends KDocumentAbstract
{
	/**
	 * Class constructore
	 *
	 * @param	array	$options Associative array of options
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);

		// Set the mime encoding
		$this->setMimeEncoding('text/html');
	}
	
	/**
	 * Get the document head data
	 *
	 * @return	array	The document head data in array form
	 */
	public function getHeadData() { }

	/**
	 * Set the document head data
	 *
	 * @param	array	$data	The document head data in array form
	 * @return	this
	 */
	public function setHeadData(array $data) { }

	/**
	 * Render the document.
	 *
	 * @param boolean 	$cache		If true, cache the output
	 * @param array		$params		Associative array of attributes
	 * @return 	The rendered data
	 */
	public function render( $cache = false, array $params = array())
	{
		parent::render();
		return $this->getBuffer();
	}
}
