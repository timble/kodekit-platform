<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

 /**
  * Template Interface
  *
  * @author  Johan Janssens <http://github.com/johanjanssens>
  * @package Kodekit\Library\Template\Interface
  */
interface TemplateInterface
{
    /**
     * Load a template by url
     *
     * @param   string  $url    The template url
     * @throws \InvalidArgumentException If the template could not be located
     * @return TemplateInterface
     */
    public function loadFile($url);

    /**
     * Load a template from a string
     *
     * @param  string   $content The template content
     * @return TemplateInterface
     */
    public function loadString($content);

    /**
     * Render the template
     *
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @return string The rendered template source
     */
    public function render(array $data = array());

    /**
     * Get a template data property
     *
     * @param   string  $property The property name.
     * @param   mixed   $default  Default value to return.
     * @return  string  The property value.
     */
    public function get($property, $default = null);

    /**
     * Get the template data
     *
     * @return  array   The template data
     */
    public function getData();

    /**
     * Register a template function
     *
     * @param string  $name      The function name
     * @param string  $callable  The callable
     * @return TemplateInterface
     */
    public function registerFunction($name, callable $function);

    /**
     * Unregister a template function
     *
     * @param string    $name   The function name
     * @return TemplateInterface
     */
    public function unregisterFunction($name);
}