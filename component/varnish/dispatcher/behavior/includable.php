<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-varnish for the canonical source repository
 */

namespace Kodekit\Component\Varnish;

use Kodekit\Library;

/**
 * Dispatcher Includable Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Varnish
 */
class DispatcherBehaviorIncludable extends Library\DispatcherBehaviorAbstract
{
    /**
     * Caching enabled
     *
     * @var bool
     */
    protected $_cache;

    /**
     * Cache path
     *
     * @var string
     */
    protected $_cache_path;

    /**
     * List of filters that are being passed through
     *
     * @var array
     */
    protected $_passthrough_filters;

    /**
     * Constructor
     *
     * @param Library\ObjectConfig $config   An optional ObjectConfig object with configuration options
     * @throws \UnexpectedValueException    If no 'template' config option was passed
     * @throws \InvalidArgumentException    If the model config option does not implement TemplateInterface
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Set caching
        $this->_cache        = $config->cache;
        $this->_cache_path   = $config->cache_path;

        //Passthrough filters
        $this->_passthrough_filters = $config->passthrough_filters;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'cache'        => \Kodekit::getInstance()->isCache(),
            'cache_path'   => '',
            'passthrough_filters' => array('script', 'style', 'meta', 'link', 'title')
        ));

        parent::_initialize($config);
    }

    /**
     *  Get the varnish cache controller
     *
     * @return ControllerCache
     */
    public function getCache()
    {
        return $this->getObject('com:varnish.controller.cache');
    }

    protected function _beforeInclude(Library\DispatcherContextInterface $context)
    {
        if($this->getCache()->canEsi())
        {
            //Prevent caching esi includes
            $this->getCache()->setEnabled(false);
        }
    }

    protected function _afterInclude(Library\DispatcherContextInterface $context)
    {
        if($this->getCache()->canEsi())
        {
            $html   = '';
            $result =  $context->result;

            foreach($this->_passthrough_filters as $filter)
            {
                if(is_string($filter) && strpos($filter, '.') === false ) {
                    $filter = 'com:varnish.template.filter.' . $filter;
                }

                $html .= $this->getObject($filter)->filter($result);
            }

            $query = array(
                'component'  => 'varnish',
                'view'       => 'fragment',
                'identifier' => (string) $context->param,
                'auth_token' => $this->getObject('com:varnish.dispatcher.authenticator.jwt')->createToken()
            );

            $route  = $this->getController()->getView()->getRoute($query, true);
            $result = '<esi:include src="'.$route.'" />'.PHP_EOL;

            if($this->getCache()->isDebug())
            {
                $format  = PHP_EOL.'<!--BEGIN esi:include '.$context->param.' -->'.PHP_EOL;
                $format .= '%s';
                $format .= PHP_EOL.'<!--END esi:include '.$context->param.' -->'.PHP_EOL;

                $result = sprintf($format, trim($result));
            }

            $context->result = $result;

            //Enable the cache again
            $this->getCache()->setEnabled(true);
        }
    }
}