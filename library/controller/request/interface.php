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
 * Controller Request Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
 */
interface ControllerRequestInterface extends HttpRequestInterface
{
    /**
     * Set the request query
     *
     * @param  array $query
     * @return ControllerRequestInterface
     */
    public function setQuery($query);

    /**
     * Get the request query
     *
     * @return HttpMessageParameters
     */
    public function getQuery();

    /**
     * Set the request data
     *
     * @param  array $data
     * @return ControllerRequestInterface
     */
    public function setData($data);

    /**
     * Get the request data
     *
     * @return HttpMessageParameters
     */
    public function getData();

    /**
     * Set the request format
     *
     * @param $format
     * @return ControllerRequestInterface
     */
    public function setFormat($format);

    /**
     * Return the request format
     *
     * @return  string  The request format or NULL if no format could be found
     */
    public function getFormat();

    /**
     * Set the user object
     *
     * @param UserInterface $user A request object
     * @return ControllerRequest
     */
    public function setUser(UserInterface $user);

    /**
     * Get the user object
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Returns the request language tag
     *
     * Should return a properly formatted IETF language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Returns the request timezone
     *
     * @return string
     */
    public function getTimezone();
}