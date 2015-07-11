<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

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
            'cache'        => \Nooku::isCache(),
            'cache_path'   => '',
            'passhthrough_filters' => array('script', 'style', 'meta', 'link', 'title')
        ));

        parent::_initialize($config);
    }

    protected function _afterInclude(Library\DispatcherContextInterface $context)
    {
        $varnish = $this->getObject('com:varnish.controller.cache');
        if($varnish->canEsi())
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

            if($varnish->isDebug())
            {
                $format  = PHP_EOL.'<!--BEGIN esi:include '.$context->param.' -->'.PHP_EOL;
                $format .= '%s';
                $format .= PHP_EOL.'<!--END esi:include '.$context->param.' -->'.PHP_EOL;

                $result = sprintf($format, trim($result));
            }

            $context->result = $result;
        }
    }
}