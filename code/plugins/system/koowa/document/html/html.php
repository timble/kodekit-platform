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
 * Provides an easy interface to parse and display an html document
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @subpackage	Html
 */

class KDocumentHtml extends KDocumentAbstract
{
	 /**
	 * Array of Header <link> tags
	 * 
	 * TODO : change this back to protected, public for now due to 
	 * direct use in mod_syndicate. Needs to be reported as bug.
	 *
	 * @var	 array
	 */
	public $_links = array();
	
	/**
	 * Array of custom tags
	 *
	 * @var	 array
	 */
	protected $_custom = array();
	

	/**
	 * Class constructor
	 *
	 * @param	array	$options Associative array of options
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		
		// Set the mime encoding
		$this->setMimeEncoding('text/html');

		//set default document metadata
		$this->setMetaData('Content-Type', $this->_mime . '; charset=' . $this->_charset , true );
		$this->setMetaData('robots', 'index, follow' );
	}

	/**
	 * Get the html document head data
	 *
	 * @return	array	The document head data in array form
	 */
	public function getHeadData()
	{
		$data = array();
		$data['title']		= $this->title;
		$data['description']= $this->description;
		$data['link']		= $this->link;
		$data['metaTags']	= $this->_metaTags;
		$data['links']		= $this->_links;
		$data['styleSheets']= $this->_styleSheets;
		$data['style']		= $this->_style;
		$data['scripts']	= $this->_scripts;
		$data['script']		= $this->_script;
		$data['custom']		= $this->_custom;
		
		return $data;
	}

	/**
	 * Set the html document head data
	 *
	 * @param	array	$data	The document head data in array form
	 * @return 	this
	 */
	public function setHeadData(array $data)
	{
		$this->title		= (isset($data['title'])) ? $data['title'] : $this->title;
		$this->description	= (isset($data['description'])) ? $data['description'] : $this->description;
		$this->link			= (isset($data['link'])) ? $data['link'] : $this->link;
		$this->_metaTags	= (isset($data['metaTags'])) ? $data['metaTags'] : $this->_metaTags;
		$this->_links		= (isset($data['links'])) ? $data['links'] : $this->_links;
		$this->_styleSheets	= (isset($data['styleSheets'])) ? $data['styleSheets'] : $this->_styleSheets;
		$this->_style		= (isset($data['style'])) ? $data['style'] : $this->_style;
		$this->_scripts		= (isset($data['scripts'])) ? $data['scripts'] : $this->_scripts;
		$this->_script		= (isset($data['script'])) ? $data['script'] : $this->_script;
		$this->_custom		= (isset($data['custom'])) ? $data['custom'] : $this->_custom;
		
		return $this;
	}
	
	/**
	 * Adds a custom html string to the head block
	 *
	 * @param 	string $html The html to add to the head
	 * @return 	this
	 */
	function addCustomTag( $html )
	{
		$this->_custom[] = trim( $html );
		return $this;
	}

	 /**
	 * Adds <link> tags to the head of the document
	 *
	 * <p>$relType defaults to 'rel' as it is the most common relation type used.
	 * ('rev' refers to reverse relation, 'rel' indicates normal, forward relation.)
	 * Typical tag: <link href="index.php" rel="Start"></p>
	 *
	 * @param	string  $href		The link that is being related.
	 * @param	string  $relation   Relation of link.
	 * @param	string  $relType	Relation type attribute.  Either rel or rev (default: 'rel').
	 * @param	array   $attributes Associative array of remaining attributes.
	 * @return 	this
	 */
	public function addHeadLink($href, $relation, $relType = 'rel', array $attribs = array())
	{
		$attribs = KHelperArray::toString($attribs);
		$generatedTag = '<link href="'.$href.'" '.$relType.'="'.$relation.'" '.$attribs;
		$this->_links[] = $generatedTag;
		return $this;
	}

	 /**
	 * Adds a shortcut icon (favicon)
	 *
	 * <p>This adds a link to the icon shown in the favorites list or on
	 * the left of the url in the address bar. Some browsers display
	 * it on the tab, as well.</p>
	 *
	 * @param	 string  $href		The link that is being related.
	 * @param	 string  $type		File type
	 * @param	 string  $relation	Relation of link
	 * @return 	this
	 */
	public function addFavicon($href, $type = 'image/x-icon', $relation = 'shortcut icon')
	{
		$href = str_replace( '\\', '/', $href );
		$this->_links[] = '<link href="'.$href.'" rel="'.$relation.'" type="'.$type.'"';
		return $this;
	}

	/**
	 * Get the contents of a document include
	 *
	 * @param string 	$type	The type of renderer
	 * @param string 	$name	 The name of the element to render
	 * @param array   	$attribs Associative array of remaining attributes.
	 * @return 	The output of the renderer
	 */
	public function getBuffer($type = null, $name = null, array $attribs = array())
	{
		$result = null;

		// If no type is specified, return the whole buffer
		if ($type === null) {
			return $this->_buffer;
		}

		if(isset($this->_buffer[$type][$name])) {
			$result = $this->_buffer[$type][$name];
		}

		// If the buffer has been explicitly turned off don't display or attempt to render
		if ($result === false) {
			return null;
		}

		if( $renderer = $this->getRenderer($type)) {
			$result = $renderer->render($name, $attribs, $result);
		}

		return $result;
	}

	/**
	 * Set the contents a document include
	 *
	 * @param 	string 	$type		The type of renderer
	 * @param 	string 	$name		oke The name of the element to render
	 * @param 	string 	$content	The content to be set in the buffer
	 * @return 	this
	 */
	public function setBuffer($contents, $type, $name = null)
	{
		$this->_buffer[$type][$name] = $contents;
		return $this;
	}

	/**
	 * Outputs the template to the browser.
	 *
	 * @param boolean 	$cache		If true, cache the output
	 * @param array		$params		Associative array of attributes
	 * @return 	The rendered data
	 */
	public function render( $caching = false, array $params = array())
	{
		// check
		$directory	= isset($params['directory']) ? $params['directory'] : 'templates';
		$template	= JFilterInput::clean($params['template'], 'cmd');
		$file		= JFilterInput::clean($params['file'], 'cmd');

		if ( !file_exists( $directory.DS.$template.DS.$file) ) {
			$template = 'system';
		}

		// Parse the template INI file if it exists for parameters and insert
		// them into the template.
		if (is_readable( $directory.DS.$template.DS.'params.ini' ) )
		{
			$content = file_get_contents($directory.DS.$template.DS.'params.ini');
			$params = new JParameter($content);
		}

		// Load the language file for the template
		$lang = KFactory::get('lib.joomla.language');
		$lang->load( 'tpl_'.$template );

		// Assign the variables
		$this->template = $template;
		$this->baseurl  = JURI::base(true);
		$this->params   = $params;

		// load
		$data = $this->_loadTemplate($directory.DS.$template, $file);

		// parse
		$data = $this->_parseTemplate($data);

		//output
		parent::render();
		return $data;
	}

	/**
	 * Count the modules based on the given condition. Empty modules are not counted.
	 *
	 * @param  string 	$condition	The condition to use
	 * @return integer  Number of modules found
	 */
	public function countModules($condition)
	{
		$result = '';

		$words = explode(' ', $condition);
		for($i = 0; $i < count($words); $i+=2)
		{
			// odd parts (modules)
			$name		= strtolower($words[$i]);
			$words[$i]	= 0;
			
			if(!isset($this->_buffer['modules'][$name])) 
			{
				$modules = JModuleHelper::getModules($name);
				$result  = $this->getBuffer('modules', $name);
				$words[$i] += !empty($result);
			}
		}
	
		$str = 'return '.implode(' ', $words).';';
		
		return eval($str);
	}

	/**
	 * Load a template file
	 *
	 * @param string 	$template	The name of the template
	 * @param string 	$filename	The actual filename
	 * @return string The contents of the template
	 */
	protected function _loadTemplate($directory, $filename)
	{
		global $mainframe, $option;
		
		$contents = '';

		//Check to see if we have a valid template file
		if ( file_exists( $directory.DS.$filename ) )
		{
			//store the file path
			$this->_file = $directory.DS.$filename;

			//get the file content
			ob_start();
			require_once $directory.DS.$filename;
			$contents = ob_get_contents();
			ob_end_clean();
		}

		// Try to find a favicon by checking the template and root folder
		$path = $directory . DS;
		$dirs = array( $path, JPATH_BASE . DS );
		foreach ($dirs as $dir )
		{
			$icon =   $dir . 'favicon.ico';
			if (file_exists( $icon ))
			{
				$path = str_replace( JPATH_BASE . DS, '', $dir );
				$path = str_replace( '\\', '/', $path );
				$this->addFavicon( JURI::base(true).'/'.$path . 'favicon.ico' );
				break;
			}
		}

		return $contents;
	}

	/**
	 * Parse a document template
	 *
	 * @param string 	$data		The data too parse
	 * @return The parsed contents of the template
	 */
	protected function _parseTemplate($data)
	{
		$replace = array();
		$matches = array();
		if(preg_match_all('#<jdoc:include\ type="([^"]+)" (.*)\/>#iU', $data, $matches))
		{
			$matches[0] = array_reverse($matches[0]);
			$matches[1] = array_reverse($matches[1]);
			$matches[2] = array_reverse($matches[2]);

			$count = count($matches[1]);

			for($i = 0; $i < $count; $i++)
			{
				$attribs = $this->_parseAttributes( $matches[2][$i] );
				$type  = $matches[1][$i];

				$name  = isset($attribs['name']) ? $attribs['name'] : null;
				$replace[$i] = $this->getBuffer($type, $name, $attribs);
			}

			$data = str_replace($matches[0], $replace, $data);
		}

		return $data;
	}
	
	/**
 	 * Method to extract key/value pairs out of a string with xml style attributes
 	 *
 	 * @param	string	$string	String containing xml style attributes
 	 * @return	array	Key/Value pairs for the attributes
 	 */
	protected function _parseAttributes( $string )
	{
	 	//Initialize variables
		$attr		= array();
		$retarray	= array();

		// Lets grab all the key/value pairs using a regular expression
		preg_match_all( '/([\w:-]+)[\s]?=[\s]?"([^"]*)"/i', $string, $attr );

		if (is_array($attr))
		{
			$numPairs = count($attr[1]);
			for($i = 0; $i < $numPairs; $i++ ) {
				$retarray[$attr[1][$i]] = $attr[2][$i];
			}
		}
		return $retarray;
	}
}