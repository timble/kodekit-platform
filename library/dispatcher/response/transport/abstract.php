<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Reponse
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Abstract Dispatcher Response Transport Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Transport
 */
abstract class DispatcherResponseTransportAbstract extends Object implements DispatcherResponseTransportInterface
{
    /**
     * Response object
     *
     * @var	object
     */
    protected $_response;

    /**
     * Constructor.
     *
     * @param 	object 	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (is_null($config->response))
        {
            throw new \InvalidArgumentException(
                'response [DispatcherResponseInterface] config option is required'
            );
        }

        if(!$config->response instanceof DispatcherResponseInterface)
        {
            throw new \UnexpectedValueException(
                'Response: '.get_class($config->response).' does not implement DispatcherResponseInterface'
            );
        }

        //Set the response
        $this->_response = $config->response;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'response' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the response object
     *
     * @return  object	The response object
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Send HTTP headers
     *
     * @return DispatcherResponseTransportDefault
     */
    public function sendHeaders()
    {
        if (!headers_sent())
        {
            $response = $this->getResponse();

            //Send the status header
            header(sprintf('HTTP/%s %d %s', $response->getVersion(), $response->getStatusCode(), $response->getStatusMessage()));

            //Send the other headers
            $headers = explode("\r\n", trim((string) $response->headers));

            foreach ($headers as $header) {
                header($header, false);
            }

            //Send the cookies
            foreach ($response->headers->getCookies() as $cookie)
            {
                setcookie(
                    $cookie->name,
                    $cookie->value,
                    $cookie->expire,
                    $cookie->path,
                    $cookie->domain,
                    $cookie->isSecure(),
                    $cookie->isHttpOnly()
                );
            }
        }

        return $this;
    }

    /**
     * Sends content for the current web response.
     *
     * @return DispatcherResponseTransportDefault
     */
    public function sendContent()
    {
        echo $this->getResponse()->getContent();
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
     * @return DispatcherResponseTransportDefault
     */
    public function send()
    {
        $response = $this->getResponse();

        if (in_array($response->getStatusCode(), array(204, 304))) {
            $response->setContent(null);
        }

        //Add the version header
        $response->headers->set('X-Nooku', array('version' => \Nooku::VERSION));

        // Fix Content-Length
        if ($response->headers->has('Transfer-Encoding')) {
            $response->headers->remove('Content-Length');
        }

        //Modifies the response so that it conforms to the rules defined for a 304 status code.
        if($response->getStatusCode() == HttpResponse::NOT_MODIFIED)
        {
            $response->setContent(null);

            $headers = array(
                'Allow',
                'Content-Encoding',
                'Content-Language',
                'Content-Length',
                'Content-MD5',
                'Content-Type',
                'Last-Modified'
            );

            // remove headers that MUST NOT be included with 304 Not Modified responses
            foreach ($headers as $header) {
                $response->headers->remove($header);
            }
        }

        //Calculates or modifies the cache-control header to a sensible, conservative value.
        $cache_control = (array) $response->headers->get('Cache-Control', null, false);

        if (empty($cache_control))
        {
            if(!$response->isCacheable()) {
                $response->headers->set('Cache-Control', 'no-cache');
            } else {
                $response->headers->set('Cache-Control', array('private', 'must-revalidate'));
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