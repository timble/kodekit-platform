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
 * Document class, provides an easy interface to parse and display a document
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @uses		KFactory
 */
abstract class KDocumentAbstract extends KObject
{
	/**
	 * Document title
	 *
	 * @var	 string
	 */
	public $title = '';

	/**
	 * Document description
	 *
	 * @var	 string
	 */
	public $description = '';

	/**
	 * Document full URL
	 *
	 * @var	 string
	 */
	public $link = '';

	/**
	 * Document base URL
	 *
	 * @var	 string
	 */
	public $base = '';

	 /**
	 * Contains the document language setting
	 *
	 * @var	 string
	 */
	public $language = 'en-gb';

	/**
	 * Contains the document direction setting
	 *
	 * @var	 string
	 */
	public $direction = 'ltr';

	/**
	 * Document generator
	 *
	 * @var		string
	 */
	protected $_generator = 'Koowa 0.7 - Open Web Publishing Framework';

	/**
	 * Document modified date
	 *
	 * @var		string
	 */
	protected $_mdate = '';

	/**
	 * Contains the character encoding string
	 *
	 * @var	 string
	 */
	protected $_charset = 'utf-8';

	/**
	 * Document mime type
	 *
	 * @var		string
	 */
	protected $_mime = '';

	/**
	 * Document namespace
	 *
	 * @var		string
	 */
	protected $_namespace = '';

	/**
	 * Document profile
	 *
	 * @var		string
	 */
	protected $_profile = '';

	/**
	 * Array of linked scripts
	 *
	 * @var		array
	 */
	protected $_scripts = array();

	/**
	 * Array of scripts placed in the header
	 *
	 * @var  array
	 */
	protected $_script = array();

	 /**
	 * Array of linked style sheets
	 *
	 * @var	 array
	 */
	protected $_styleSheets = array();

	/**
	 * Array of included style declarations
	 *
	 * @var	 array
	 */
	protected $_style = array();

	/**
	 * Array of meta tags
	 *
	 * @var	 array
	 */
	protected $_metaTags = array();

	/**
	 * The rendering engine
	 *
	 * @var	 object
	 */
	protected $_engine = null;

	/**
	 * Array of buffered output
	 *
	 * @var		mixed (depends on the renderer)
	 */
	protected $_buffer = null;


	/**
	 * Class constructor
	 *
	 * @param	array	$options Associative array of options
	 */
	public function __construct( array $options = array())
	{
		if (array_key_exists('charset', $options)) {
			$this->setCharset($options['charset']);
		}

		if (array_key_exists('language', $options)) {
			$this->setLanguage($options['language']);
		}

		 if (array_key_exists('direction', $options)) {
			$this->setDirection($options['direction']);
		}

		if (array_key_exists('link', $options)) {
			$this->setLink($options['link']);
		}

		if (array_key_exists('base', $options)) {
			$this->setBase($options['base']);
		}
		
		 // Mixin the KMixinClass
        $this->mixin(new KMixinClass($this, 'Document'));
		
		// Assign the classname with values from the config
		if(isset($options['name'])) {
        	$this->setClassName($options['name']);
		}
	}

	/**
	 * Returns the document type
	 *
	 * @return	string
	 */
	public function getType() 
	{
		return $this->getClassName('suffix');
	}

	/**
	 * Get the document head data
	 *
	 * @return	array	The document head data in array form
	 */
	abstract public function getHeadData();

	/**
	 * Set the document head data
	 *
	 * @param	array	$data	The document head data in array form
	 * @return	this
	 */
	abstract public function setHeadData(array $data);

	/**
	 * Get the contents of the document buffer
	 *
	 * @return 	The contents of the document buffer
	 */
	public function getBuffer() 
	{
		return $this->_buffer;
	}

	/**
	 * Set the contents of the document buffer
	 *
	 * @param string 	$content	The content to be set in the buffer
	 * @return	this
	 */
	public function setBuffer($content) 
	{
		$this->_buffer = $content;
		return $this;
	}

	/**
	 * Gets a meta tag.
	 *
	 * @param	string	$name			Value of name or http-equiv tag
	 * @param	bool	$http_equiv	 	META type "http-equiv" defaults to null
	 * @return	string
	 */
	public function getMetaData($name, $http_equiv = false)
	{
		$result = '';
		$name = strtolower($name);
		
		switch($name)
		{
			case 'generator' :  
				$result = $this->getGenerator();
				break;
				
			case 'description' :
				$result = $this->getDescription();
				break;
				
			default :
				
				$type = ($http_equiv == true) ? 'http-equiv' : 'standard';
				
				if(isset($this->_metaTags[$type][$name])) {
					$result = $this->_metaTags[$type][$name];
				}
		}
		
		return $result;
	}

	/**
	 * Sets or alters a meta tag.
	 *
	 * @param string  $name			Value of name or http-equiv tag
	 * @param string  $content		Value of the content tag
	 * @param bool	$http_equiv	 META type "http-equiv" defaults to null
	 * @return	this
	 */
	public function setMetaData($name, $content, $http_equiv = false)
	{
	$name = strtolower($name);
		
		switch($name)
		{
			case 'generator' :  
				$this->setGenerator($content);
				break;
				
			case 'description' :
				$this->setDescription($content);
				break;
				
			default :
				
				$type = ($http_equiv == true) ? 'http-equiv' : 'standard';
				$this->_metaTags[$type][$name] = $content;
		}
		return $this;
	}

	 /**
	 * Adds a linked script to the page
	 *
	 * @param	string  $url		URL to the linked script
	 * @param	string  $type		Type of script. Defaults to 'text/javascript'
	 * @return	this
	 */
	public function addScript($url, $type="text/javascript") 
	{
		$this->_scripts[$url] = $type;
		return $this;
	}

	/**
	 * Adds a script to the page
	 *
	 * @param	string  $content   Script
	 * @param	string  $type	Scripting mime (defaults to 'text/javascript')
	 * @return	this
	 */
	public function addScriptDeclaration($content, $type = 'text/javascript')
	{
		if (!isset($this->_script[strtolower($type)])) {
			$this->_script[strtolower($type)] = $content;
		} else {
			$this->_script[strtolower($type)] .= chr(13).$content;
		}
		return $this;
	}

	/**
	 * Adds a linked stylesheet to the page
	 *
	 * @param	string  $url	URL to the linked style sheet
	 * @param	string  $type   Mime encoding type
	 * @param	string  $media  Media type that this stylesheet applies to
	 * @return	this
	 */
	public function addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array())
	{
		$this->_styleSheets[$url]['mime']		= $type;
		$this->_styleSheets[$url]['media']		= $media;
		$this->_styleSheets[$url]['attribs']	= $attribs;
		return $this;
	}

	 /**
	 * Adds a stylesheet declaration to the page
	 *
	 * @param	string  $content   Style declarations
	 * @param	string  $type		Type of stylesheet (defaults to 'text/css')
	 * @return	this
	 */
	public function addStyleDeclaration($content, $type = 'text/css')
	{
		if (!isset($this->_style[strtolower($type)])) {
			$this->_style[strtolower($type)] = $content;
		} else {
			$this->_style[strtolower($type)] .= chr(13).$content;
		}
		return $this;
	}

	 /**
	 * Sets the document charset
	 *
	 * @param   string   $type  Charset encoding string
	 * @return	this
	 */
	public function setCharset($type = 'utf-8') 
	{
		$this->_charset = $type;
		return $this;
	}

	/**
	 * Returns the document charset encoding.
	 *
	 * @return string
	 */
	public function getCharset() 
	{
		return $this->_charset;
	}

	/**
	 * Sets the global document language declaration. Default is English (en-gb).
	 *
	 * @param   string   $lang
	 * @return	this
	 */
	public function setLanguage($lang = "en-gb") 
	{
		$this->language = strtolower($lang);
		return $this;
	}

	/**
	 * Returns the document language.
	 *
	 * @return string
	 * 
	 */
	public function getLanguage() 
	{
		return $this->language;
	}

	/**
	 * Sets the global document direction declaration. Default is left-to-right (ltr).
	 *
	 * @param   string   $dir
	 * @return	this
	 */
	public function setDirection($dir = "ltr") 
	{
		$this->direction = strtolower($dir);
		return $this;
	}

	/**
	 * Returns the document language.
	 *
	 * @return string
	 */
	public function getDirection() 
	{
		return $this->direction;
	}

	/**
	 * Sets the title of the document
	 *
	 * @param	string	$title
	 * @return	this
	 */
	public function setTitle($title) 
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * Return the title of the document.
	 *
	 * @return   string
	 */
	public function getTitle() 
	{
		return $this->title;
	}

	/**
	 * Sets the base URI of the document
	 *
	 * @param	string	$base
	 * @return	this
	 */
	public function setBase($base) 
	{
		$this->base = $base;
		return $this;
	}

	/**
	 * Return the base URI of the document.
	 *
	 * @return   string
	 */
	public function getBase() 
	{
		return $this->base;
	}

	/**
	 * Sets the description of the document
	 *
	 * @param	string	$description
	 * @return	this
	 */
	public function setDescription($description) 
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * Return the title of the page.
	 *
	 * @access   public
	 */
	public function getDescription() 
	{
		return $this->description;
	}

	 /**
	 * Sets the document link
	 *
	 * @param   string   $url  A url
	 * @return	this
	 */
	public function setLink($url) 
	{
		$this->link = $url;
		return $this;
	}

	/**
	 * Returns the document base url
	 *
	 * @return string
	 */
	public function getLink() 
	{
		return $this->link;
	}

	 /**
	 * Sets the document generator
	 *
	 * @param   string  $generator
	 * @return	this
	 */
	public function setGenerator($generator) 
	{
		$this->_generator = $generator;
		return $this;
	}

	/**
	 * Returns the document generator
	 *
	 * @return string
	 */
	public function getGenerator() 
	{
		return $this->_generator;
	}

	 /**
	 * Sets the document modified date
	 *
	 * @param   string 	$date
	 * @return	this
	 */
	public function setModifiedDate($date) 
	{
		$this->_mdate = $date;
		return $this;
	}

	/**
	 * Returns the document modified date
	 *
	 * @return string
	 */
	public function getModifiedDate() 
	{
		return $this->_mdate;
	}

	/**
	 * Sets the document MIME encoding that is sent to the browser.
	 *
	 * This usually will be text/html because most browsers cannot yet
	 * accept the proper mime settings for XHTML: application/xhtml+xml
	 * and to a lesser extent application/xml and text/xml. See the W3C note
	 * ({@link http://www.w3.org/TR/xhtml-media-types/
	 * http://www.w3.org/TR/xhtml-media-types/}) for more details.
	 *
	 * @param	string	$type
	 * @return	this
	 */
	public function setMimeEncoding($type = 'text/html') 
	{
		$this->_mime = strtolower($type);
		return $this;
	}
	
	/**
	 * Get the document MIME encoding that is sent to the browser.
	 *
	 * @param	string	$type
	 */
	public function getMimeEncoding($type = 'text/html') 
	{
		return $this->_mime;
	}

	/**
	 * Load a renderer
	 *
	 * @param	string	$type 	The renderer type
	 * @return	object
	 */
	public function loadRenderer( $type )
	{
		$instance = KFactory::tmp('lib.koowa.document.'.$this->getType().'.renderer.'.$type, array('document' => $this));
		return $instance;
	}

	/**
	 * Outputs the document
	 *
	 * @param 	boolean 	$cache		If true, cache the output
	 * @param 	boolean 	$compress	If true, compress the output
	 * @param 	array		$params		Associative array of attributes
	 * @return 	mixed		The rendered data
	 */
	public function render( $cache = false, array $params = array())
	{
		JResponse::setHeader( 'Expires', gmdate( 'D, d M Y H:i:s', time() + 900 ) . ' GMT' );
		JResponse::setHeader( 'Content-Type', $this->_mime .  '; charset=' . $this->_charset);
		
		if ($mdate = $this->getModifiedDate()) {
			JResponse::setHeader( 'Last-Modified', $mdate );
		}
	}
}