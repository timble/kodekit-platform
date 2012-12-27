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
 * Abstract Controller Response Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Response
 */
abstract class KControllerResponseAbstract extends KHttpResponse implements KControllerResponseInterface
{
    /**
     * Send HTTP headers
     *
     * @return KControllerResponseAbstract
     */
    public function sendHeaders()
    {
        if (headers_sent()) {
            return $this;
        }

        //Send the status header
        header(sprintf('HTTP/%s %d %s', $this->getVersion(), $this->getStatusCode(), $this->getStatusMessage()));

        //Send the other headers
        $headers = explode("\r\n", trim((string) $this->headers));

        foreach ($headers as $header) {
            header($header, false);
        }

        //Send the cookies
        foreach ($this->headers->getCookies() as $cookie) {
            setcookie($cookie->name, $cookie->value, $cookie->expire, $cookie->path, $cookie->domain, $cookie->isSecure(), $cookie->isHttpOnly());
        }

        return $this;
    }

    /**
     * Sends content for the current web response.
     *
     * @return KControllerResponseAbstract
     */
    public function sendContent()
    {
        echo $this->getContent();
        return $this;
    }

    /**
     * Send HTTP response
     *
     * Prepares the Response before it is sent to the client. This method tweaks the headers to ensure that
     * it is compliant with RFC 2616 and calculates or modifies the cache-control header to a sensible and
     * conservative value
     *
     * @see http://tools.ietf.org/html/rfc2616
     * @return KControllerResponseAbstract
     */
    public function send()
    {
        if (in_array($this->_status_code, array(204, 304))) {
            $this->setContent(null);
        }

        //Add the version header
        $this->headers->set('X-Koowa', array('version' => Koowa::VERSION));

        // Fix Content-Length
        if ($this->headers->has('Transfer-Encoding')) {
            $this->headers->remove('Content-Length');
        }

        //Modifies the response so that it conforms to the rules defined for a 304 status code.
        if($this->getStatusCode() == self::NOT_MODIFIED)
        {
            $this->setContent(null);

            // remove headers that MUST NOT be included with 304 Not Modified responses
            foreach (array('Allow', 'Content-Encoding', 'Content-Language', 'Content-Length', 'Content-MD5', 'Content-Type', 'Last-Modified') as $header) {
                $this->headers->remove($header);
            }
        }

        //Calculates or modifies the cache-control header to a sensible, conservative value.
        $cache_control = (array) $this->headers->get('Cache-Control', null, false);

        if (empty($cache_control))
        {
            if(!$this->isCacheable()) {
                $this->headers->set('Cache-Control', 'no-cache');
            } else {
                $this->headers->set('Cache-Control', array('private', 'must-revalidate'));
            }
        }

        //Send headers and content
        $this->sendHeaders()
             ->sendContent();

        //Flush output to client
        if (!function_exists('fastcgi_finish_request'))
        {
            if (PHP_SAPI !== 'cli')
            {
                for ($i = 0; $i < ob_get_level(); $i++) {
                    ob_end_flush();
                }

                flush();
            }
        }
        else fastcgi_finish_request();

        return $this;
    }
}