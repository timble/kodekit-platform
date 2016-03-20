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
 * Template Filter Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Template
 */
interface TemplateFilterInterface extends ObjectHandlable
{
    /**
     * Priority levels
     */
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH    = 2;
    const PRIORITY_NORMAL  = 3;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 5;

    /**
     * Filter the text
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function filter(&$text);

    /**
     * Get the template object
     *
     * @return  TemplateInterface	The template object
     */
    public function getTemplate();

    /**
     * Get the priority of the filter
     *
     * @return  integer The priority level
     */
    public function getPriority();

    /**
     * Method to extract key/value pairs out of a string with xml style attributes
     *
     * @param   string  $string String containing xml style attributes
     * @return  array   Key/Value pairs for the attributes
     */
    public function parseAttributes($string);

    /**
     * Method to build a string with xml style attributes from  an array of key/value pairs
     *
     * @param   mixed   $array The array of Key/Value pairs for the attributes
     * @return  string  String containing xml style attributes
     */
    public function buildAttributes($array);
}