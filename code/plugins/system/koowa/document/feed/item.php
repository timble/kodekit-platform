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
 * Stores feed item information
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package		Koowa_Document
 * @subpackage	Feed
 */
class KDocumentFeedItem extends KObject
{
	/**
	 * Title item element
	 *
	 * required
	 *
	 * @var		string
	 */
	public $title;

	/**
	 * Link item element
	 *
	 * required
	 *
	 * @var		string
	 */
	public $link;

	/**
	 * Description item element
	 *
	 * required
	 *
	 * @var		string
	 */
	 public $description;

	/**
	 * Author item element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $author;

	 /**
	  * Author email element
	  *
	  * optional
	  *
	  * @var		string
	  */
	 public $authorEmail;

	/**
	 * Category element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $category;

	 /**
	 * Comments element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $comments;

	 /**
	 * Enclosure element
	 *
	 * @var		object
	 */
	 public $enclosure =  null;

	 /**
	 * Guid element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $guid;

	/**
	 * Published date
	 *
	 * optional
	 *
	 *  May be in one of the following formats:
	 *
	 *	RFC 822:
	 *	"Mon, 20 Jan 03 18:05:41 +0400"
	 *	"20 Jan 03 18:05:41 +0000"
	 *
	 *	ISO 8601:
	 *	"2003-01-20T18:05:41+04:00"
	 *
	 *	Unix:
	 *	1043082341
	 *
	 * @var		string
	 */
	 public $pubDate;

	 /**
	 * Source element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $source;

	 /**
	 * Set the KDocumentFeedEnclosure for this item
	 *
	 * @param 	KDocumentFeedEnclosure $enclosure The KDocumentFeedEnclosure to add to the feed.
	 * @return 	this
	 */
	 public function setEnclosure($enclosure)	
	 {
		 $this->enclosure = $enclosure;
		 return $this;
	 }
}