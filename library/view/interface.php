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
 * View Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
	 * option, view and layout can be ommitted. The following variations will all result in the same route
	 *
	 * - foo=bar
	 * - option=com_mycomp&view=myview&foo=bar
	 *
	 * In templates, use route()
	 *
	 * @param	string	$route  The query string used to create the route
	 * @param 	boolean	$fqr    If TRUE create a fully qualified route. Default TRUE.
     * @param 	boolean	$escape If TRUE escapes the route for xml compliance. Default TRUE.
	 * @return 	string 	The route
	 */
	public function getRoute($route, $fqr = null, $escape = null);

    /**
     * Get the chain of command object
     *
     * To increase performance a reference to the command chain is stored in object scope to prevent slower calls
     * to the CommandChain mixin.
     *
     * @return  CommandChainInterface
     */
    public function getCommandChain();

    /**
     * Get the view context
     *
     * @return  ViewContext
     */
    public function getContext();
}