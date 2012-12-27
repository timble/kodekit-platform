<?php
/**
 * @version		$Id: abstract.php 4948 2012-09-03 23:05:48Z johanjanssens $
 * @package		Koowa_Controller
 * @subpackage  Request
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Controller Request Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Request
 */
interface KControllerRequestInterface extends KHttpRequestInterface
{
    /**
     * Set the request query
     *
     * @param  array $query
     * @return KControllerResponseInterface
     */
    public function setQuery($query);

    /**
     * Get the request query
     *
     * @return KHttpMessageParameters
     */
    public function getQuery();

    /**
     * Set the request data
     *
     * @param  array $data
     * @return KControllerResponseInterface
     */
    public function setData($data);

    /**
     * Get the request data
     *
     * @return KHttpMessageParameters
     */
    public function getData();

    /**
     * Return the request format
     *
     * @param string $default The default format
     * @return  string  The request format or NULL if no format could be found
     */
    public function getFormat($default = 'html');
}