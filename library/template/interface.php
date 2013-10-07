<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

 /**
  * Template Interface
  *
  * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
  * @package Nooku\Library\Template
  */
interface TemplateInterface
{
    const STATUS_LOADED    = 1;
    const STATUS_COMPILED  = 2;
    const STATUS_EVALUATED = 4;
    const STATUS_RENDERED  = 8;

    /**
     * Load a template by path
     *
     * @param   string  $path     The template path
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @param   integer $status   The template state
     * @return TemplateInterface
     */
    public function load($path, $data = array(), $status = self::STATUS_LOADED);

    /**
     * Parse and compile the template to PHP code
     *
     * This function passes the template through compile filter queue and returns the result.
     *
     * @return string The parsed data
     */
    public function compile();

    /**
     * Evaluate the template using a simple sandbox
     *
     * This function writes the template to a temporary file and then includes it.
     *
     * @return string The evaluated data
     * @see tempnam()
     */
    public function evaluate();

    /**
     * Render the template
     *
     * @param  array    $data       An associative array of data to be extracted in local template scope
     * @return string    The rendered data
     */
    public function render();

    /**
     * Escape a string
     *
     * By default the function uses htmlspecialchars to escape the string
     *
     * @param string $string String to to be escape
     * @return string Escaped string
     */
    public function escape($string);

    /**
     * Translates a string and handles parameter replacements
     *
     * @param string $string String to translate
     * @param array  $parameters An array of parameters
     * @return string Translated string
     */
    public function translate($string, array $parameters = array());

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
     * Get the template contents
     *
     * @return  string
     */
    public function getContent();

    /**
     * Set the template content from a string
     *
     * @param  string   $string     The template content
     * @param  integer  $status     The template state
     * @return TemplateAbstract
     */
    public function setContent($content, $status = self::STATUS_LOADED);

    /**
     * Get the format
     *
     * @return 	string 	The format of the view
     */
    public function getFormat();

    /**
     * Get the view object attached to the template
     *
     * @return  ViewInterface
     */
	public function getView();

    /**
     * Method to set a view object attached to the template
     *
     * @param mixed  $view An object that implements ObjectInterface, ObjectIdentifier object
     *                     or valid identifier string
     * @throws \UnexpectedValueException    If the identifier is not a view identifier
     * @return TemplateInterface
     */
	public function setView($view);

    /**
     * Get a filter by identifier
     *
     * @param   mixed    $filter    An object that implements ObjectInterface, ObjectIdentifier object
                                    or valid identifier string
     * @param   array    $config    An optional associative array of configuration settings
     * @return TemplateFilterInterface
     */
    public function getFilter($filter, $config = array());

    /**
     * Attach a filter for template transformation
     *
     * @param   mixed  $filter An object that implements ObjectInterface, ObjectIdentifier object
     *                         or valid identifier string
     * @param   array $config  An optional associative array of configuration settings
     * @return TemplateInterface
     */
    public function attachFilter($filter, $config = array());

    /**
     * Get a template helper
     *
     * @param    mixed    $helper ObjectIdentifierInterface
     * @param    array    $config An optional associative array of configuration settings
     * @return  TemplateHelperInterface
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
     * Register a template locator
     *
     * @param TemplateLocatorInterface $locator
     * @return TemplateAbstract
     */
    public function registerLocator(TemplateLocatorInterface $locator);

    /**
     * Get a registered template locator based on his type
     *
     * @return TemplateLocatorInterface|null  Returns the template locator or NULL if the locator can not be found.
     */
    public function getLocator($type, $config = array());

    /**
     * Check if the template is loaded
     *
     * @return boolean  Returns TRUE if the template is loaded. FALSE otherwise
     */
    public function isLoaded();

    /**
     * Check if the template is compiled
     *
     * @return boolean  Returns TRUE if the template is compiled. FALSE otherwise
     */
    public function isCompiled();

    /**
     * Check if the template is evaluated
     *
     * @return boolean  Returns TRUE if the template is evaluated. FALSE otherwise
     */
    public function isEvaluated();

    /**
     * Check if the template is rendered
     *
     * @return boolean  Returns TRUE if the template is rendered. FALSE otherwise
     */
    public function isRendered();
}