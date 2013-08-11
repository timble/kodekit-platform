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
 * JSON Dispatcher Response Transport
 *
 * Response represents an HTTP response in JSON format.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherResponseTransportJson extends DispatcherResponseTransportAbstract
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
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_padding = $config->padding;
    }

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
            'padding' => '',
        ));

        parent::_initialize($config);
    }

    /**
     * Sets the JSONP callback.
     *
     * @param string $callback
     * @throws \InvalidArgumentException If the padding is not a valid javascript identifier
     * @return DispatcherResponseTransportJson
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
     * @return DispatcherResponseTransportJson
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
     * @return DispatcherResponseTransportJson
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