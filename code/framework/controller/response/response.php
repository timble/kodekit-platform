<?php
/**
 * @version		$Id: abstract.php 4948 2012-09-03 23:05:48Z johanjanssens $
 * @package		Koowa_Controller
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Controller Response Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Response
 */
class KControllerResponse extends KHttpResponse implements KControllerResponseInterface
{
    /**
     * Sets a redirect
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3
     *
     * @param  string   $location   The redirect location
     * @param  string   $message    The redirect message
     * @param  string   $code       The redirect status code
     * @throws \InvalidArgumentException If the location is empty
     * @throws \UnexpectedValueException If the location is not a string, or cannot be cast to a string
     * @return KDispatcherResponse
     */
    public function setRedirect($location, $message = null, $code = self::SEE_OTHER)
    {
        if (empty($location)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        if (!is_string($location) && !is_numeric($location) && !is_callable(array($location, '__toString')))
        {
            throw new \UnexpectedValueException(
                'The Response location must be a string or object implementing __toString(), "'.gettype($location).'" given.'
            );
        }

        $this->setStatus($code, $message);
        $this->_headers->set('Location', (string) $location);
        return $this;
    }

    /**
     * Implement a virtual 'headers' class property to return their respective objects.
     *
     * @param   string $name  The property name.
     * @return  string $value The property value.
     */
    public function __get($name)
    {
        if($name == 'headers') {
            return $this->getHeaders();
        }

        return parent::__get($name);
    }
}