<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

 /**
  * Template Filterable Interface
  *
  * @author  Johan Janssens <http://github.com/johanjanssens>
  * @package Nooku\Library\Template\Interface
  */
interface TemplateFilterable
{
    /**
     * Filter template content
     *
     * @return string The filtered template source
     */
    public function filter();

    /**
     * Add a filter for template transformation
     *
     * @param   mixed  $filter An object that implements ObjectInterface, ObjectIdentifier object
     *                         or valid identifier string
     * @param   array $config  An optional associative array of configuration settings
     * @return TemplateInterface
     */
    public function addFilter($filter, $config = array());

    /**
     * Check if a filter exists
     *
     * @param 	string	$filter The name of the filter
     * @return  boolean	TRUE if the filter exists, FALSE otherwise
     */
    public function hasFilter($filter);

    /**
     * Create a filter by identifier
     *
     * @param   mixed    $filter    An object that implements ObjectInterface, ObjectIdentifier object
                                    or valid identifier string
     * @return TemplateFilterInterface
     */
    public function getFilter($filter);
}
