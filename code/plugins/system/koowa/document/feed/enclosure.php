<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Stores feed enclosure information
 *
 * @author		Johan Janssens <johan@joomlatools.org>
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