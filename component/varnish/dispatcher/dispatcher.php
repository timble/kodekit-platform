<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-varnish for the canonical source repository
 */

namespace Kodekit\Component\Varnish;

use Kodekit\Library;

/**
 * Http Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Varnish
 */
class Dispatcher extends Library\DispatcherAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	Library\ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'authenticators' => array('jwt'),
        ));

        parent::_initialize($config);
    }

    /**
     * Resolve the request
     *
     * @param Library\DispatcherContext $context A dispatcher context object
     * @throw DispatcherExceptionMethodNotAllowed If the HTTP request method is not allowed.
     */
    protected function _resolveRequest(Library\DispatcherContext $context)
    {
        if ($context->request->getMethod() !== Library\HttpRequest::GET) {
            throw new Library\DispatcherExceptionMethodNotAllowed('Method not allowed');
        }

        parent::_resolveRequest($context);
    }

    /**
     * Dispatch the request
     *
     * Dispatch to a controller internally or forward to another component.  Functions makes an internal sub-request,
     * based on the information in the request and passing along the context.
     *
     * @param Library\DispatcherContext $context   A dispatcher context object
     * @return	mixed
     */
    protected function _actionDispatch(Library\DispatcherContext $context)
    {
        if($context->getRequest()->getQuery()->has('identifier'))
        {
            $identifier = $context->getRequest()->getQuery()->get('identifier', 'string');

            $url        = $this->getObject('lib:http.url', array('url' => $identifier));
            $identifier = $this->getIdentifier($url->toString(Library\HttpUrl::BASE));

            //Create the dispatcher
            $config = array(
                'response'   => $this->getObject('response'),
                'dispatched' => true,
            );

            //Render the component
            $this->getObject('com:'.$identifier->package.'.dispatcher.fragment', $config)
                ->dispatch($url);
        }

        return parent::_actionDispatch($context);
    }
}
