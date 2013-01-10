<?php
/**
 * @version		$Id$
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

 /**
  * Template Interface
  * 
  * @author		Johan Janssens <johan@nooku.org>
  * @package	Koowa_Template
  */
interface KTemplateInterface
{
    /**
     * Get the template file identifier
     *
     * @return	KServiceIdentifier
     */
    public function getFile();
	
	/**
	 * Get the template data
	 * 
	 * @return	mixed
	 */
	public function getData();

	/**
	 * Get the template object stack
 	 *
	 * @return 	KTemplateStack
	 */
	public function getStack();
	
	/**
	 * Get the view object attached to the template
	 *
	 * @return	KViewInterface
	 */
	public function getView();

	/**
	 * Method to set a view object attached to the template
	 *
	 * @param	mixed	An object that implements KObjectServiceable, KServiceIdentifier object 
	 * 					or valid identifier string
	 * @throws	\UnexpectedValueException	If the identifier is not a view identifier
	 * @return	KTemplateInterface
	 */
	public function setView($view);
	
	/**
	 * Load a template by identifier
	 * 
	 * This functions only accepts full identifiers of the format
	 * -  com:[//application/]component.view.[.path].name
	 *
	 * @param   string 	The template identifier
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @param	boolean	If TRUE process the data using a tmpl stream. Default TRUE.
	 * @return KTemplateInterface
	 */
	public function loadIdentifier($template, $data = array(), $process = true);
	
	/**
	 * Load a template by path
	 *
	 * @param   string 	The template path
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @param	boolean	If TRUE process the data using a tmpl stream. Default TRUE.
	 * @return KTemplateInterface
	 */
	public function loadFile($file, $data = array(), $process = true);
	
	/**
	 * Load a template from a string
	 *
	 * @param   string 	The template contents
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @param	boolean	If TRUE process the data using a tmpl stream. Default TRUE.
	 * @return KTemplateInterface
	 */
	public function loadString($string, $data = array(), $process = true);

	/**
	 * Render the template
	 * 
	 * This function passes the template through write filter chain and returns the
	 * result.
	 *
	 * @return string	The rendered data
	 */
	public function render();
	
	/**
	 * Parse the template
	 * 
	 * This function passes the template through read filter chain and returns the
	 * result.
	 *
	 * @return string	The parsed data
	 */
	public function parse();

    /**
     * Get a filter by identifier
     *
     * @return KTemplateFilterInterface
     */
    public function getFilter($filter, $config = array());

    /**
     * Attach one or more filters for template transformation
     *
     * @param array 	Array of one or more behaviors to add.
     * @return KTemplateInterface
     */
    public function attachFilter($filters);

    /**
     * Get a template helper
     *
     * @param	mixed	KServiceIdentifierInterface
     * @param	array	An optional associative array of configuration settings
     * @return 	KTemplateHelperInterface
     */
    public function getHelper($helper, $config = array());
	
	/**
	 * Load a template helper
	 * 
	 * This functions accepts a partial identifier, in the form of helper.function. If a partial
	 * identifier is passed a full identifier will be created using the template identifier.
	 *
	 * @param	string	Name of the helper, dot separated including the helper function to call
	 * @param	array	An optional associative array of configuration settings
	 * @return 	string	Helper output
	 */
	public function renderHelper($identifier, $config = array());

    /**
     * Searches for the file
     *
     * @param	string	The file path to look for.
     * @return	mixed	The full path and file name for the target file, or FALSE
     * 					if the file is not found
     */
    public function findFile($file);
}