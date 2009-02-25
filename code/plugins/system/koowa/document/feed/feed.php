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
 * Provides an easy interface to parse and display any feed document
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @subpackage	Feed
 * @uses		KInput
 */
class KDocumentFeed extends KDocumentAbstract
{
	/**
	 * Syndication URL feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $syndicationURL = "";

	 /**
	 * Image feed element
	 *
	 * optional
	 *
	 * @var		object
	 */
	 public $image = null;

	/**
	 * Copyright feed elememnt
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $copyright = "";

	 /**
	 * Published date feed element
	 *
	 *  optional
	 *
	 * @var		string
	 */
	 public $pubDate = "";

	 /**
	 * Lastbuild date feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $lastBuildDate = "";

	 /**
	 * Editor feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $editor = "";

	/**
	 * Docs feed element
	 *
	 * @var		string
	 */
	 public $docs = "";

	 /**
	 * Editor email feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $editorEmail = "";

	/**
	 * Webmaster email feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $webmaster = "";

	/**
	 * Category feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $category = "";

	/**
	 * TTL feed attribute
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $ttl = "";

	/**
	 * Rating feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $rating = "";

	/**
	 * Skiphours feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $skipHours = "";

	/**
	 * Skipdays feed element
	 *
	 * optional
	 *
	 * @var		string
	 */
	 public $skipDays = "";

	/**
	 * The feed items collection
	 *
	 * @var array
	 */
	public $items = array();

	/**
	 * Class constructor
	 *
	 * @param	array	$options Associative array of options
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);
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
	 * Render the document
	 *
	 * @param boolean 	$cache		If true, cache the output
	 * @param array		$params		Associative array of attributes
	 * @return 	The rendered data
	 */
	public function render( $cache = false, array $params = array())
	{
		global $option;

		// Get the feed type
		$type = KInput::get('type', 'GET', 'cmd', 'cmd', 'rss');
		
		// Set the mime encoding
		$this->setMimeEncoding('application/'.$type.'+xml');

		/*
		 * Cache TODO In later release
		 */
		$cache		= 0;
		$cache_time = 3600;
		$cache_path = JPATH_BASE.DS.'cache';

		// set filename for rss feeds
		$file = strtolower( str_replace( '.', '', $type ) );
		$file = $cache_path.DS.$file.'_'.$option.'.xml';

		// Instantiate feed renderer and set the mime encoding
		$renderer = $this->getRenderer($type);
		
		//output
		// Generate prolog
		$data	= "<?xml version=\"1.0\" encoding=\"".$this->_charset."\"?>\n";
		$data	.= "<!-- generator=\"".$this->getGenerator()."\" -->\n";

		 // Generate stylesheet links
		foreach ($this->_styleSheets as $src => $attr ) {
			$data .= "<?xml-stylesheet href=\"$src\" type=\"".$attr['mime']."\"?>\n";
		}

		// Render the feed
		$data .= $renderer->render(null);

		parent::render();
		return $data;
	}

	/**
	 * Adds an KDocumentFeedItem to the feed.
	 *
	 * @param 	KDocumentFeedItem $item The feeditem to add to the feed.
	 * @return 	this
	 */
	public function addItem( $item )
	{
		$item->source = $this->link;
		$this->items[] = $item;
		
		return $this;
	}
}