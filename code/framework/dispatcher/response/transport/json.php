<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * JSON Dispatcher Response Transport Class
 *
 * Response represents an HTTP response in JSON format.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Response
 */
class KDispatcherResponseTransportJson extends KDispatcherResponseTransportDefault
{
    /**
     * The padding for JSONP
     *
     * @var string
     */
    protected $_padding;

    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_padding = $config->padding;
    }

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param     object     An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'padding' => '',
        ));

        parent::_initialize($config);
    }

    /**
     * Sets the JSONP callback.
     *
     * @param string $callback
     * @throws \InvalidArgumentException If the padding is not a valid javascript identifier
     * @return KDispatcherResponseTransportJson
     */
    public function setCallback($callback)
    {
        // taken from http://www.geekality.net/2011/08/03/valid-javascript-identifier/
        $pattern = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
        $parts = explode('.', $callback);

        foreach ($parts as $part)
        {
            if (!preg_match($pattern, $part)) {
                throw new \InvalidArgumentException('The callback name is not valid.');
            }
        }

        $this->_padding = $callback;
    }

    /**
     * Sends content for the current web response.
     *
     * @return KDispatcherResponseTransportJson
     */
    public function sendContent()
    {
        if (!empty($this->_padding))
        {
            $response = $this->getResponse();
            $response->setContent(sprintf('%s(%s);', $this->_padding, $response->getContent()));
        }

        return parent::sendContent();
    }

    /**
     * Send HTTP response
     *
     * If not padding is set inspect the request query for a 'callback' parameter and use this.
     *
     * @see http://tools.ietf.org/html/rfc2616
     * @return KDispatcherResponseTransportJson
     */
    public function send()
    {
        //If not padding is set inspect the request query.
        if(empty($this->_padding))
        {
            $request = $this->getResponse()->getRequest();

            if($request->query->has('callback')) {
                $this->setCallback($request->query->get('callback', 'cmd'));
            }
        }

        return parent::send();
    }


}