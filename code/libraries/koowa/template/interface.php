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
     * @return	string
     */
    public function getPath();
	
	/**
	 * Get the template data
	 * 
	 * @return	mixed
	 */
	public function getData();

    /**
     * Get the template object stack
     *
     * @return  KTemplateStack
     */
	public function getStack();

    /**
     * Get the template contents
     *
     * @return  string
     */
    public function getContents();

    /**
     * Get the view object attached to the template
     *
     * @return  KViewInterface
     */
	public function getView();

    /**
     * Method to set a view object attached to the template
     *
     * @param mixed  $view An object that implements KObjectServiceable, KServiceIdentifier object
     *                     or valid identifier string
     * @throws \UnexpectedValueException    If the identifier is not a view identifier
     * @return KTemplateAbstract
     */
	public function setView($view);

    /**
     * Load a template by identifier
     *
     * This functions only accepts full identifiers of the format
     * -  com:[//application/]component.view.[.path].name
     *
     * @param   string   $template  The template identifier
     * @param   array    $data      An associative array of data to be extracted in local template scope
     * @throws \InvalidArgumentException If the template could not be found
     * @return KTemplateAbstract
     */
	public function loadIdentifier($template, $data = array());

    /**
     * Load a template by path
     *
     * @param   string  $file     The template path
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @return KTemplateAbstract
     */
	public function loadFile($file, $data = array());

    /**
     * Load a template from a string
     *
     * @param  string   $string     The template contents
     * @param  array    $data       An associative array of data to be extracted in local template scope
     * @return KTemplateAbstract
     */
	public function loadString($string, $data = array());

    /**
     * Render the template
     *
     * This function passes the template through write filter chain and returns the result.
     *
     * @return string    The rendered data
     */
	public function render();

    /**
     * Get a filter by identifier
     *
     * @param   mixed    $filter    An object that implements KObjectServiceable, KServiceIdentifier object
                                    or valid identifier string
     * @param   array    $config    An optional associative array of configuration settings
     * @return KTemplateFilterInterface
     */
    public function getFilter($filter, $config = array());

    /**
     * Attach one or more filters for template transformation
     *
     * @param array $filters Array of one or more behaviors to add.
     * @return KTemplateAbstract
     */
    public function attachFilter($filters);

    /**
     * Get a template helper
     *
     * @param    mixed    $helper KServiceIdentifierInterface
     * @param    array    $config An optional associative array of configuration settings
     * @return  KTemplateHelperInterface
     */
    public function getHelper($helper, $config = array());

    /**
     * Load a template helper
     *
     * This functions accepts a partial identifier, in the form of helper.function. If a partial identifier is passed a
     * full identifier will be created using the template identifier.
     *
     * @param    string   $identifier Name of the helper, dot separated including the helper function to call
     * @param    array    $params     An optional associative array of functions parameters to be passed to the helper
     * @return   string   Helper output
     * @throws   \BadMethodCallException If the helper function cannot be called.
     */
	public function renderHelper($identifier, $config = array());

    /**
     * Searches for the file
     *
     * @param   string  $file The file path to look for.
     * @return  mixed   The full path and file name for the target file, or FALSE if the file is not found
     */
    public function findFile($file);
}