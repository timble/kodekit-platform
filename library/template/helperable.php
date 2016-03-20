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
  * Template Helperable Interface
  *
  * @author  Johan Janssens <http://github.com/johanjanssens>
  * @package Kodekit\Library\Template\Interface
  */
interface TemplateHelperable
{
    /**
     * Invoke a template helper
     *
     * This function accepts a partial identifier, in the form of helper.method or schema:package.helper.method. If
     * a partial identifier is passed a full identifier will be created using the template identifier.
     *
     * If the view state have the same string keys, then the parameter value for that key will overwrite the state.
     *
     * @param    string   $identifier Name of the helper, dot separated including the helper function to call
     * @param    array    $params     An optional associative array of functions parameters to be passed to the helper
     * @return   string   Helper output
     * @throws   \BadMethodCallException If the helper function cannot be called.
     */
    public function invokeHelper($identifier, $config = array());

    /**
     * Create a template helper
     *
     * @param    mixed    $helper ObjectIdentifierInterface
     * @param    array    $config An optional associative array of configuration settings
     * @return  TemplateHelperInterface
     */
    public function createHelper($helper, $config = array());
}
