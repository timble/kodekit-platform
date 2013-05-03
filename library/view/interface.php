<?php
/**
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * View Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package		Koowa_View
 */
interface ViewInterface
{
    /**
     * Render the view
     *
     * @return string The output of the view
     */
    public function render();

    /**
     * Set a view property
     *
     * @param   string  $property The property name.
     * @param   mixed   $value    The property value.
     * @return ViewAbstract
     */
    public function set($property, $value);

    /**
     * Get a view property
     *
     * @param   string  $property   The property name.
     * @return  string  The property value.
     */
    public function get($property);

    /**
	 * Get the name
	 *
	 * @return 	string 	The name of the object
	 */
	public function getName();
	
	/**
	 * Get the format
	 *
	 * @return 	string 	The format of the view
	 */
	public function getFormat();

    /**
     * Get the content
     *
     * @return  string The content of the view
     */
    public function getContent();

    /**
     * Get the content
     *
     * @param  string $content The content of the view
     * @return ViewAbstract
     */
    public function setContent($content);

	/**
	 * Get the model object attached to the controller
	 *
	 * @return	ModelAbstract
	 */
	public function getModel();
	
	/**
	 * Method to set a model object attached to the view
	 *
	 * @param	mixed	$model  An object that implements ObjectInterface, ObjectIdentifier object
	 * 					        or valid identifier string
	 * @throws	\UnexpectedValueException	If the identifier is not a model identifier
	 * @return	ViewAbstract
	 */
    public function setModel($model);

	/**
	 * Get a route based on a full or partial query string 
	 * 
	 * option, view and layout can be ommitted. The following variations will all result in the same route
	 *
	 * - foo=bar
	 * - option=com_mycomp&view=myview&foo=bar
	 *
	 * In templates, use @route()
	 *
	 * @param	string	$route  The query string used to create the route
	 * @param 	boolean	$fqr    If TRUE create a fully qualified route. Default TRUE.
     * @param 	boolean	$escape If TRUE escapes the route for xml compliance. Default TRUE.
	 * @return 	string 	The route
	 */
	public function getRoute($route, $fqr = null, $escape = null);
}