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
 * Default Dispatcher Response Transport
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherResponseTransportHttp extends DispatcherResponseTransportAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	ObjectConfig $config 	An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_LOW,
        ));

        parent::_initialize($config);
    }

    /**
     * Send HTTP headers
     *
     * @param DispatcherResponseInterface $response
     * @return DispatcherResponseTransportAbstract
     */
    public function sendHeaders(DispatcherResponseInterface $response)
    {
        if (!headers_sent())
        {
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
     * @param DispatcherResponseInterface $response
     * @return DispatcherResponseTransportAbstract
     */
    public function sendContent(DispatcherResponseInterface $response)
    {
        echo $response->getStream()->getContent();
        return $this;
    }

    /**
     * Send HTTP response
     *
     * Prepares the Response before it is sent to the client. This method tweaks the headers to ensure that
     * it is compliant with RFC 2616 and calculates or modifies the cache-control header to a sensible and
     * conservative value
     *
     * @link http://tools.ietf.org/html/rfc2616
     *
     * @param DispatcherResponseInterface $response
     * @return boolean  Returns true if the response has been send, otherwise FALSE
     */
    public function send(DispatcherResponseInterface $response)
    {
        $request = $response->getRequest();

        //Make sure we do not have body content for 204, 205 and 305 status codes
        $codes = array(HttpResponse::NO_CONTENT, HttpResponse::NOT_MODIFIED, HttpResponse::RESET_CONTENT);
        if (in_array($response->getStatusCode(), $codes)) {
            $response->setContent(null);
        }

        //Remove location header if we are not redirecting and the status code is not 201
        if(!$response->isRedirect() && $response->getStatusCode() !== HttpResponse::CREATED)
        {
            if($response->headers->has('Location')) {
                $response->headers->remove('Location');
            }
        }

        //Add file related information if we are serving a file
        if($response->isDownloadable())
        {
            //Last-Modified header
            $time = $response->getStream()->getTime(FilesystemStream::TIME_MODIFIED);
            $response->setLastModified($time);

            //Disposition header
            $response->headers->set('Content-Disposition', array(
                'inline',
                'filename' => '"'.basename($response->getStream()->getPath()).'"',
            ));

            //Force a download by the browser by setting the disposition to 'attachment'.
            if($response->isAttachable())
            {
                $response->setContentType('application/force-download');
                $response->headers->set('Content-Disposition', array(
                    'attachment',
                    'filename'  => '"'.basename($response->getStream()->getPath()).'"',
                ));
            }
        }

        //Add Last-Modified header if not present
        if(!$response->headers->has('Last-Modified')) {
            $response->setLastModified(new \DateTime('now'));
        }

        //Add Content-Length if not present
        if(!$response->headers->has('Content-Length')) {
            $response->headers->set('Content-Length', $response->getStream()->getSize());
        }

        //Remove Content-Length for transfer encoded responses that do not contain a content range
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

            //Remove headers that MUST NOT be included with 304 Not Modified responses
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
        $this->sendHeaders($response)
             ->sendContent($response);

        return headers_sent();
    }
}