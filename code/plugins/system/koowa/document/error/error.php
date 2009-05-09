<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @subpackage 	Error
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Provides an easy interface to parse and display an error page
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @subpackage	Error
 */
class KDocumentError extends KDocumentAbstract
{
	/**
	 * Error Object
	 * 
	 * @var	object
	 */
	protected  $_error = null;

	/**
	 * Class constructor
	 *
	 * @param	array	$attributes Associative array of attributes
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
	 * Set error object
	 *
	 * @param	object	$error	Error object to set
	 * @return	boolean	True on success
	 */
	public function setError($error)
	{
		if (JError::isError($error)) {
			$this->_error = & $error;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Render the document
	 *
	 * @param boolean 	$cache		If true, cache the output
	 * @param array		$params		Associative array of attributes
	 */
	public function render( $cache = false, $params = array())
	{
		// If no error object is set return null
		if (!isset($this->_error)) {
			return;
		}

		//Set the status header
		JResponse::setHeader('status', $this->_error->code.' '.str_replace( "\n", ' ', $this->_error->message ));
		$file = 'error.php';

		// check template
		$directory	= isset($params['directory']) ? $params['directory'] : 'templates';
		$template	= isset($params['template']) ? JFilterInput::clean($params['template'], 'cmd') : 'system';

		if ( !file_exists( $directory.DS.$template.DS.$file) ) {
			$template = 'system';
		}

		//set variables
		$this->baseurl  = KRequest::base();
		$this->template = $template;
		$this->debug	= isset($params['debug']) ? $params['debug'] : false;
		$this->error	= $this->_error;

		// load
		$data = $this->_loadTemplate($directory.DS.$template, $file);

		parent::render();
		return $data;
	}

	/**
	 * Load a template file
	 *
	 * @param string 	$template	The name of the template
	 * @param string 	$filename	The actual filename
	 * @return string The contents of the template
	 */
	public function _loadTemplate($directory, $filename)
	{
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

		return $contents;
	}

	public function renderBacktrace()
	{
		$contents	= null;
		$backtrace	= $this->_error->getTrace();
		if( is_array( $backtrace ) )
		{
			ob_start();
			$j	=	1;
			echo  	'<table border="0" cellpadding="0" cellspacing="0" class="Table">';
			echo  	'	<tr>';
			echo  	'		<td colspan="3" align="left" class="TD"><strong>Call stack</strong></td>';
			echo  	'	</tr>';
			echo  	'	<tr>';
			echo  	'		<td class="TD"><strong>#</strong></td>';
			echo  	'		<td class="TD"><strong>Function</strong></td>';
			echo  	'		<td class="TD"><strong>Location</strong></td>';
			echo  	'	</tr>';
			for( $i = count( $backtrace )-1; $i >= 0 ; $i-- )
			{
				echo  	'	<tr>';
				echo  	'		<td class="TD">'.$j.'</td>';
				if( isset( $backtrace[$i]['class'] ) ) {
					echo  	'	<td class="TD">'.$backtrace[$i]['class'].$backtrace[$i]['type'].$backtrace[$i]['function'].'()</td>';
				} else {
					echo  	'	<td class="TD">'.$backtrace[$i]['function'].'()</td>';
				}
				if( isset( $backtrace[$i]['file'] ) ) {
					echo  	'		<td class="TD">'.$backtrace[$i]['file'].':'.$backtrace[$i]['line'].'</td>';
				} else {
					echo  	'		<td class="TD">&nbsp;</td>';
				}
				echo  	'	</tr>';
				$j++;
			}
			echo  	'</table>';
			$contents = ob_get_contents();
			ob_end_clean();
		}
		return $contents;
	}
}