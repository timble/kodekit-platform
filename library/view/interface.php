<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * View Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\View
 */
interface ViewInterface
{
    /**
     * Execute an action by triggering a method in the derived class.
     *
     * @param   array $data The view data
     * @return  string  The output of the view
     */
    public function render($data = array());

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
     * @param  mixed  $default  Default value to return.
     * @return  string  The property value.
     */
    public function get($property, $default = null);

    /**
     * Check if a view property exists
     *
     * @param   string  $property   The property name.
     * @return  boolean TRUE if the property exists, FALSE otherwise
     */
    public function has($property);

    /**
     * Sets the view data
     *
     * @param   array $data The view data
     * @return  ViewAbstract
     */
    public function setData($data);

    /**
     * Get the view data
     *
     * @return  array   The view data
     */
    public function getData();

    /**
	 * Get the name
	 *
	 * @return 	string 	The name of the object
	 */
	public function getName();

    /**
     * Get the title
     *
     * @return 	string 	The title of the view
     */
    public function getTitle();

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
     * Get the view url
     *
     * @return  HttpUrl  A HttpUrl object
     */
    public function getUrl();

    /**
     * Set the view url
     *
     * @param HttpUrl $url   A HttpUrl object or a string
     * @return  ViewAbstract
     */
    public function setUrl(HttpUrl $url);

	/**
	 * Get a route based on a full or partial query string
	 *
	 * option, view and layout can be omitted. The following variations will all result in the same route
	 *
	 * - foo=bar
	 * - component=mycomp&view=myview&foo=bar
	 *
	 * In templates, use route()
	 *
	 * @param	string	$route  The query string used to create the route
	 * @param 	boolean	$fqr    If TRUE create a fully qualified route. Default TRUE.
     * @param 	boolean	$escape If TRUE escapes the route for xml compliance. Default TRUE.
	 * @return 	DispatcherRouterRoute 	The route
	 */
	public function getRoute($route, $fqr = true, $escape = true);

    /**
     * Get the view context
     *
     * @return  ViewContext
     */
    public function getContext();

    /**
     * Returns the views output
     *
     * @return string
     */
    public function toString();

    /**
     * Check if we are rendering an entity collection
     *
     * @return bool
     */
    public function isCollection();
}