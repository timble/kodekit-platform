<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @copyright   Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Stores feed image information
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package		Koowa_Document
 * @subpackage	Feed
 */
class KDocumentFeedImage extends KObject
{
	/**
	 * Title image attribute
	 *
	 * required
	 *
	 * @var		string
	 */
	 public $title = "";

	 /**
	 * URL image attribute
	 *
	 * required
	 *
	 * @var		string
	 */
	public $url = "";

	/**
	 * Link image attribute
	 *
	 * required
	 *
	 * @var		string
	 */
	 public $link = "";

	 /**
	 * witdh image attribute
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $width;

	 /**
	 * Title feed attribute
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $height;

	 /**
	 * Title feed attribute
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $description;
}