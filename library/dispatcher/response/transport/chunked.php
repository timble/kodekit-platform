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
 * Chunked Stream Dispatcher Response Transport
 *
 * Streaming or chunked transfer encoding is a data transfer mechanism in version HTTP 1.1 in which a web server serves
 * content in a series of chunks. This uses the Transfer-Encoding HTTP response header instead of the Content-Length
 * header, which the protocol would otherwise require. Because the Content-Length header is not used, the server does
 * not need to know the length of the content before it starts transmitting a response to the client.
 *
 * Web servers can begin transmitting responses with dynamically-generated content before knowing the total size of
 * that content. The size of each chunk is sent right before the chunk itself so that a client can tell when it has
 * finished receiving data for that chunk. The data transfer is terminated by a final chunk of length zero.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 * @see http://en.wikipedia.org/wiki/Chunked_transfer_encoding
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.16
 */
class DispatcherResponseTransportChunked extends DispatcherResponseTransportHttp
{
    /**
     * Byte offset
     *
     * @var	integer
     */
    protected $_offset;

    /**
     * Byte range
     *
     * @var	integer
     */
    protected $_range;

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'chunk_size' => 8 * (1024*1024), //In Bytes
            'priority'   => self::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the byte offset
     *
     * @param DispatcherResponseInterface $response
     * @return int The byte offset
     * @throws HttpExceptionRangeNotSatisfied   If the byte offset is outside of the total size of the file
     */
    public function getOffset(DispatcherResponseInterface $response)
    {
        if(!isset($this->_offset))
        {
            $offset = 0;

            if($response->getRequest()->isStreaming())
            {
                $ranges = $response->getRequest()->getRanges();

                if (!empty($ranges[0]['first'])) {
                    $offset = (int) $ranges[0]['first'];
                }

                if ($offset > $this->getFileSize($response)) {
                    throw new HttpExceptionRangeNotSatisfied('Invalid range');
                }
            }

            $this->_offset = $offset;
        }

        return $this->_offset;
    }

    /**
     * Get the byte range
     *
     * @param DispatcherResponseInterface $response
     * @return int The last byte offset
     */
    public function getRange(DispatcherResponseInterface $response)
    {
        if(!isset($this->_range))
        {
            $length = $this->getFileSize($response);
            $range  = $length - 1;

            if($response->getRequest()->isStreaming())
            {
                $ranges = $response->getRequest()->getRanges();

                if (!empty($ranges[0]['last'])) {
                    $range = (int) $ranges[0]['last'];
                }

                if($range > $length - 1) {
                    $range = $length - 1;
                }
            }

            $this->_range = $range;
        }

        return $this->_range;
    }

    /**
     * Get the chunk size
     *
     * @param DispatcherResponseInterface $response
     * @return int The chunk size in bytes
     */
    public function getChunkSize()
    {
        return $this->getConfig()->chunk_size;
    }

    /**
     * Set the chunk size
     *
     * @param DispatcherResponseInterface $response
     * @return int The chunk size in bytes
     */
    public function setChunkSize($size)
    {
        $this->getConfig()->chunk_size = $size;
        return $this;
    }

    /**
     * Get the file size
     *
     * @param DispatcherResponseInterface $response
     * @return int The file size in bytes
     */
    public function getFileSize(DispatcherResponseInterface $response)
    {
        return $response->getStream()->getSize();
    }

    /**
     * Generate an etag from a file stream
     *
     * This functions returns a md5 hash of same format as Apache does. Eg "%ino-%size-%mtime" using the file info.
     * @link http://stackoverflow.com/questions/44937/how-do-you-make-an-etag-that-matches-apache
     *
     * @param DispatcherResponseInterface $response
     * @return string
     */
    public function getFileEtag(DispatcherResponseInterface $response)
    {
        $info = $response->getStream()->getInfo();
        $etag = sprintf('"%x-%x-%s"', $info['ino'], $info['size'],base_convert(str_pad($info['mtime'],16,"0"),10,16));

        return $etag;
    }

    /**
     * Sends content for the current web response.
     *
     * We flush the data to the output buffer based on the chunk size and range information provided in the request.
     * The default chunk size is 8 MB.
     *
     * @param DispatcherResponseInterface $response
     * @return DispatcherResponseTransportRedirect
     */
    public function sendContent(DispatcherResponseInterface $response)
    {
        if ($response->isSuccess())
        {
            //For a certain unmentionable browser
            if(ini_get('zlib.output_compression')) {
                @ini_set('zlib.output_compression', 'Off');
            }

            //Fix for IE7/8
            if(function_exists('apache_setenv')) {
                apache_setenv('no-gzip', '1');
            }

            //Remove PHP time limit
            if(!ini_get('safe_mode')) {
                @set_time_limit(0);
            }

            $stream  = $response->getStream();

            $offset = $this->getOffset($response);
            $range  = $this->getRange($response);
            $chunk  = $this->getChunkSize();

            if ($offset > 0) {
                $stream->seek($offset);
            }

            $stream->flush($chunk, $range);
            $stream->close();

            return $this;
        }

        parent::sendContent($response);
    }

    /**
     * Send HTTP response
     *
     * @param DispatcherResponseInterface $response
     * @return boolean
     */
    public function send(DispatcherResponseInterface $response)
    {
        $request  = $response->getRequest();

        if($response->isStreamable())
        {
            //Explicitly set the Accept Ranges header to bytes to inform client we accept range requests
            $response->headers->set('Accept-Ranges', 'bytes');

            //Set a file etag
            $response->headers->set('etag', $this->getFileEtag($response));

            if($request->isStreaming())
            {
                if($response->isSuccess())
                {
                    //Default Content-Type Header
                    if(!$response->headers->has('Content-Type')) {
                        $response->headers->set('Content-Type', 'application/octet-stream');
                    }

                    //Transfer Encoding Headers
                    $response->headers->set('Transfer-Encoding', 'chunked');

                    //Content Range Headers
                    $offset = $this->getOffset($response);
                    $range  = $this->getRange($response);
                    $size   = $this->getFileSize($response);

                    $response->setStatus(HttpResponse::PARTIAL_CONTENT);
                    $response->headers->set('Content-Range', sprintf('bytes %s-%s/%s', $offset, $range, $size));
                }

                if($response->isError())
                {
                    /**
                     * A server sending a response with status code 416 (Requested range not satisfiable) SHOULD include a
                     * Content-Range field with a byte-range- resp-spec of "*". The instance-length specifies the current
                     * length of the selected resource.
                     *
                     * @see : http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.16
                     */
                    if($response->getStatusCode() == HttpResponse::REQUESTED_RANGE_NOT_SATISFIED)
                    {
                        $size = $this->getFileSize($response);
                        $response->headers->set('Content-Range', sprintf('bytes */%s', $size));
                    }
                }
            }

            return parent::send($response);
        }
    }
}