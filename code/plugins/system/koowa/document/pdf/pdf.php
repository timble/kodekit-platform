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
 * Provides an easy interface to parse and display a pdf document
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @subpackage	Pdf
 * @uses		KFactory
 */
class KDocumentPdf extends KDocumentAbstract
{
	protected $_engine	= null;

	protected $_name	= 'koowa';

	protected $_header	= null;

	protected $_margin_header	= 5;
	protected $_margin_footer	= 10;
	protected $_margin_top		= 27;
	protected $_margin_bottom	= 25;
	protected $_margin_left		= 15;
	protected $_margin_right	= 15;

	// Scale ratio for images [number of points in user unit]
	protected $_image_scale	= 4;

	/**
	 * Class constructore
	 *
	 * @access protected
	 * @param	array	$options Associative array of options
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);

		if (isset($options['margin-header'])) {
			$this->_margin_header = $options['margin-header'];
		}

		if (isset($options['margin-footer'])) {
			$this->_margin_footer = $options['margin-footer'];
		}

		if (isset($options['margin-top'])) {
			$this->_margin_top = $options['margin-top'];
		}

		if (isset($options['margin-bottom'])) {
			$this->_margin_bottom = $options['margin-bottom'];
		}

		if (isset($options['margin-left'])) {
			$this->_margin_left = $options['margin-left'];
		}

		if (isset($options['margin-right'])) {
			$this->_margin_right = $options['margin-right'];
		}

		if (isset($options['image-scale'])) {
			$this->_image_scale = $options['image-scale'];
		}
		
		// Set the mime encoding
		$this->setMimeEncoding('application/pdf');

		/*
		 * Setup external configuration options
		 */
		define('K_TCPDF_EXTERNAL_CONFIG', true);

		/*
		 * Path options
		 */

		// Installation path
		define("K_PATH_MAIN", JPATH_LIBRARIES.DS."tcpdf");

		// URL path
		define("K_PATH_URL", JPATH_BASE);

		// Fonts path
		define("K_PATH_FONTS", JPATH_SITE.DS.'language'.DS."pdf_fonts".DS);

		// Cache directory path
		define("K_PATH_CACHE", K_PATH_MAIN.DS."cache");

		// Cache URL path
		define("K_PATH_URL_CACHE", K_PATH_URL.DS."cache");

		// Images path
		define("K_PATH_IMAGES", K_PATH_MAIN.DS."images");

		// Blank image path
		define("K_BLANK_IMAGE", K_PATH_IMAGES.DS."_blank.png");

		/*
		 * Format options
		 */

		// Cell height ratio
		define("K_CELL_HEIGHT_RATIO", 1.25);

		// Magnification scale for titles
		define("K_TITLE_MAGNIFICATION", 1.3);

		// Reduction scale for small font
		define("K_SMALL_RATIO", 2/3);

		// Magnication scale for head
		define("HEAD_MAGNIFICATION", 1.1);

		/*
		 * Create the pdf document
		 */

		Koowa::loadFile('lib.tcpdf.tcpdf');

		// Default settings are a portrait layout with an A4 configuration using millimeters as units
		$this->_engine = new TCPDF();

		//set margins
		$this->_engine->SetMargins($this->_margin_left, $this->_margin_top, $this->_margin_right);
		//set auto page breaks
		$this->_engine->SetAutoPageBreak(TRUE, $this->_margin_bottom);
		$this->_engine->SetHeaderMargin($this->_margin_header);
		$this->_engine->SetFooterMargin($this->_margin_footer);
		$this->_engine->setImageScale($this->_image_scale);
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
	 * Sets the document name
	 *
	 * @param   string   $name	Document name
	 * @return  this
	 */
	public function setName($name = 'koowa') 
	{
		$this->_name = $name;
		return $this;
	}

	/**
	 * Returns the document name
	 *
	 * @return string
	 */
	public function getName() 
	{
		return $this->_name;
	}

	 /**
	 * Sets the document header string
	 *
	 * @param   string   $text	Document header string
	 * @return  this
	 */
	public function setHeader($text) 
	{
		$this->_header = $text;
		return $this;
	}

	/**
	 * Returns the document header string
	 *
	 * @return string
	 */
	public function getHeader() 
	{
		return $this->_header;
	}

	/**
	 * Render the document.
	 *
	 * @param boolean 	$cache		If true, cache the output
	 * @param array		$params		Associative array of attributes
	 * @return 	The rendered data
	 */
	public function render( $cache = false, array $params = array())
	{
		$pdf = $this->_engine;

		// Set PDF Metadata
		$pdf->SetCreator($this->getGenerator());
		$pdf->SetTitle($this->getTitle());
		$pdf->SetSubject($this->getDescription());
		$pdf->SetKeywords($this->getMetaData('keywords'));

		// Set PDF Header data
		$pdf->setHeaderData('',0,$this->getTitle(), $this->getHeader());

		// Set PDF Header and Footer fonts
		$lang = KFactory::get('lib.joomla.language');;
		$font = $lang->getPdfFontName();
		$font = ($font) ? $font : 'freesans';

		$pdf->setRTL($lang->isRTL());

		$pdf->setHeaderFont(array($font, '', 10));
		$pdf->setFooterFont(array($font, '', 8));

		// Initialize PDF Document
		$pdf->AliasNbPages();
		$pdf->AddPage();

		// Build the PDF Document string from the document buffer
		$this->fixLinks();
		$pdf->WriteHTML($this->getBuffer(), true);
		$data = $pdf->Output('', 'S');

		// Set document type headers
		parent::render();

		//JResponse::setHeader('Content-Length', strlen($data), true);

		JResponse::setHeader('Content-disposition', 'inline; filename="'.$this->getName().'.pdf"', true);

		//Close and output PDF document
		return $data;
	}
}