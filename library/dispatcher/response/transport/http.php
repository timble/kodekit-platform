<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Default Dispatcher Response Transport
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
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
        echo $response->getStream()->toString();
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

        // IIS does not like it when you have a Location header in a non-redirect request
        // http://stackoverflow.com/questions/12074730/w7-pro-iis-7-5-overwrites-php-location-header-solved
        if ($response->headers->has('Location') && !$response->isRedirect())
        {
            $server = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : getenv('SERVER_SOFTWARE');

            if ($server && strpos(strtolower($server), 'microsoft-iis') !== false) {
                $response->headers->remove('Location');
            }
        }

        //Add file related information if we are serving a file
        if($response->isDownloadable())
        {
            //Last-Modified header
            if($time = $response->getStream()->getTime(FilesystemStreamInterface::TIME_MODIFIED)) {
                $response->setLastModified($time);
            };

            $user_agent = $response->getRequest()->getAgent();
            // basename does not work if the string starts with a UTF character
            $filename   = ltrim(basename(' '.strtr($response->getStream()->getPath(), array('/' => '/ '))));

            // Android cuts file names after #
            if (stripos($user_agent, 'Android')) {
                $filename = str_replace('#', '_', $filename);
            }

            $disposition = array('filename' => '"'.$filename.'"');

            // IE7 and 8 accepts percent encoded file names as the filename value
            // Other browsers (except Safari) use filename* header starting with UTF-8''
            $encoded_name = rawurlencode($filename);

            if($encoded_name !== $filename)
            {
                if (preg_match('/(?i)MSIE [4-8]/i', $user_agent)) {
                    $disposition['filename'] = '"'.$encoded_name.'"';
                }
                elseif (!stripos($user_agent, 'AppleWebkit')) {
                    $disposition['filename*'] = 'UTF-8\'\''.$encoded_name;
                }
            }

            //Disposition header
            $response->headers->set('Content-Disposition', array_merge(array('inline'), $disposition));

            //Force a download by the browser by setting the disposition to 'attachment'.
            if($response->isAttachable())
            {
                $response->setContentType('application/octet-stream');
                $response->headers->set('Content-Disposition', array_merge(array('attachment'), $disposition));
            }

            //Explicitly disable the IE pause button
            if(!$response->headers->has('Accept-Ranges')) {
                $response->headers->set('Accept-Ranges', 'none');
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

        // Prevent caching: Cache-control needs to be empty for IE on SSL.
        // See: http://support.microsoft.com/default.aspx?scid=KB;EN-US;q316431
        if ($request->isSecure() && preg_match('#(?:MSIE |Internet Explorer/)(?:[0-9.]+)#', $request->getAgent())) {
            header('Cache-Control: ');
        }

        //Send headers and content
        $this->sendHeaders($response)
             ->sendContent($response);

        return parent::send($response);
    }
}