<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @subpackage 	Feed
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.koowa.org
 */

/**
 * Stores feed enclosure information
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @package		Koowa_Document
 * @subpackage	Feed
 */
class KDocumentFeedEnclosure extends KObject
{
	/**
	 * URL enclosure element
	 *
	 * required
	 *
	 * @var		string
	 */
	 public $url = "";

	/**
	 * Lenght enclosure element
	 *
	 * required
	 *
	 * @var		string
	 */
	 public $length = "";

	 /**
	 * Type enclosure element
	 *
	 * required
	 *
	 * @var		string
	 */
	 public $type = "";
}