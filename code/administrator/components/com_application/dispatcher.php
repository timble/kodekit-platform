<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Application Dispatcher Class
.*
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationDispatcher extends KControllerAbstract implements KServiceInstantiatable
{
    /**
     * The site identifier.
     *
     * @var string
     */
    protected $_site;

    /**
     * The application message queue.
     *
     * @var	array
     */
    protected $_message_queue = array();

    /**
     * The application options
     *
     * @var KConfig
     */
    protected $_options = null;

    /**
     * Constructor.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('before.run', array($this, 'loadConfig'));
        $this->registerCallback('before.run', array($this, 'loadSession'));
        $this->registerCallback('before.run', array($this, 'loadLanguage'));

        //Set exception handler
        set_exception_handler(array($this, 'error'));

        // Set the connection options
        $this->_options = $config->options;

        //Setup the request
        KRequest::root(str_replace('/administrator', '', KRequest::base()));

        //Set the site name
        if(empty($config->site)) {
            $this->_site = $this->_findSite();
        } else {
            $this->_site = $config->site;
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'site'      => null,
            'options'   => array(
                'session_name' => 'admin',
                'config_file'  => JPATH_ROOT.'/configuration.php',
                'language'     => null
            ),
            'request'	 => KRequest::get('get', 'string'),
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KServiceInterface object
     * @return KDispatcherDefault
     */
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        // Check if an instance with this identifier already exists or not
        if (!$container->has($config->service_identifier))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $container->setAlias('application', $config->service_identifier);
        }

        return $container->get($config->service_identifier);
    }

    protected function _actionRun(KCommandContext $context)
    {
        //Define application flow
        $this->registerCallback('after.run'      , array($this, 'route'));
        $this->registerCallback('after.route'    , array($this, 'authorize'));
        $this->registerCallback('after.authorize', array($this, 'dispatch'));
        $this->registerCallback('after.dispatch' , array($this, 'render'));

        //Set the site error reporting
        $error_reporting = $this->getCfg('error_reporting');
        if ($error_reporting > 0)
        {
            //Development mode
            if($error_reporting == 1)
            {
                error_reporting( E_ALL | E_STRICT | ~E_DEPRECATED );
                ini_set( 'display_errors', 1 );

            }

            //Production mode
            if($error_reporting == 2)
            {
                error_reporting( E_ERROR | E_WARNING | E_PARSE );
                ini_set( 'display_errors', 0 );
            }
        }

        //Set the site debug mode
        define( 'KDEBUG', $this->getCfg('debug') );

        //Set the paths
        $params = $this->getService('application.components')->files->params;

        define('JPATH_FILES'  , JPATH_SITES.'/'.$this->getSite());
        define('JPATH_IMAGES' , JPATH_SITES.'/'.$this->getSite().'/'.$params->get('image_path', 'images'));
        define('JPATH_CACHE'  , $this->getCfg('cache_path', JPATH_ROOT.'/cache'));

        // Set timezone to user's setting, falling back to global configuration.
        try {
		    $timezone = new DateTimeZone(JFactory::getUser()->getParam('timezone'));
		} catch(Exception $e) {
		    $timezone = new DateTimeZone($this->getCfg('timezone'));
		}
		
		date_default_timezone_set($timezone->getName());
    }

    protected function _actionRoute(KCommandContext $context)
    {
        $url = clone KRequest::url();

        //Parse the route
        $this->getRouter()->parse($url);

        //Set the request
        $this->setRequest($url->query);
    }

    protected function _actionAuthorize(KCommandContext $context)
    {
        $user = JFactory::getUser();
        if(!in_array($user->users_group_id, array(23, 24, 25))) {
            $this->option = 'com_users';
        }

        if(!isset($this->getRequest()->option)) {
            $this->option = 'com_dashboard';
        }

        //@TODO : Can be removed after refactor of KRequest
        JRequest::set($this->getRequest(), 'get', false );
    }

    /**
     * Dispatch the application
     *
     * Dispatching is the process of pulling the option from the request object an mapping them to a component. If the
     * component does not exist an exception is thrown
     *
     * @param KCommandContext $context	A command context object
     * @throws KException	When the commponent can not be found
     */
    protected function _actionDispatch(KCommandContext $context)
    {
        $component = $this->option;

        if(!empty($component))
        {
            $name = substr( $component, 4);

            if (!$this->getService('application.components')->isEnabled($name))
            {
                throw new KException(JText::_('Component Not Found'), KHttpResponse::NOT_FOUND);
                return false;
            }

            //Load common language files
            $lang = JFactory::getLanguage()->load($component);

            //Load the component aliasses
            KLoader::loadIdentifier('com://admin/'.$name.'.aliases');

            $result = $this->getService('com://admin/'.$name.'.dispatcher')->dispatch();
            return $result;
        }
        else throw new KException(JText::_('Component Not Found'), KHttpResponse::NOT_FOUND);
    }

    /**
     * Render the page
     *
     * Rendering is the process of pushing the document buffers into the template placeholders, retrieving data from the
     * document and pushing it into the JResponse buffer.
     *
     * @param KCommandContext $context	A command context object
     */
    protected function _actionRender(KCommandContext $context)
    {
        $config = array(
            'request' => $this->getRequest(),
        );

        $controller = $this->getService('com://admin/application.controller.page', $config);
        $controller->getView()
            ->content($context->result)
            ->option($this->getRequest()->option)
            ->tmpl(KRequest::get('get.tmpl', 'cmd', 'default'));

        //Render the page controller
        $content = $controller->display($context);

        //Make images paths absolute
        $path = KRequest::root()->getPath().'/'.str_replace(JPATH_ROOT.DS, '', JPATH_IMAGES.'/');

        $content = str_replace(JURI::base().'images/', $path, $content);
        $content = str_replace(array('../images', './images') , '"'.$path, $content);

        //@TODO :Setup the response
        //JResponse::setHeader( 'Expires', gmdate( 'D, d M Y H:i:s', time() + 900 ) . ' GMT' );
        //if ($mdate = $this->getModifiedDate()) {
        //    JResponse::setHeader( 'Last-Modified', $mdate /* gmdate( 'D, d M Y H:i:s', time() + 900 ) . ' GMT' */ );
        //}
        //JResponse::setHeader( 'Content-Type', $this->_mime .  '; charset=' . $this->_charset);

        JResponse::setBody($content);
        echo JResponse::toString($this->getCfg('gzip'));
        exit(0);
    }

    /**
     * Render the error
     *
     * We're wrapping it in a try catch block to avoid exceptions thrown inside the handler. Exceptions thrown in the
     * handler leads to debugging nightmare.
     *
     * @link http://www.php.net/manual/en/function.set-exception-handler.php#88082
     * @param KCommandContext $context	A command context object
     */
    protected function _actionError(KCommandContext $context)
    {
        try
        {
            $data = $this->getService('com://admin/application.controller.error')
                ->format(KRequest::format() ? KRequest::format() : 'html')
                ->display($context);
        }
        catch (Exception $e) {
            $data = get_class($e)." thrown within the exception handler. Message: ".$e->getMessage()." on line ".$e->getLine();
        }

        //@TODO :Setup the response
        //JResponse::setHeader( 'Expires', gmdate( 'D, d M Y H:i:s', time() + 900 ) . ' GMT' );
        //if ($mdate = $this->getModifiedDate()) {
        //    JResponse::setHeader( 'Last-Modified', $mdate /* gmdate( 'D, d M Y H:i:s', time() + 900 ) . ' GMT' */ );
        //}
        //JResponse::setHeader( 'Content-Type', $this->_mime .  '; charset=' . $this->_charset);

        JResponse::setBody($data);
        echo JResponse::toString();
        exit(0);
    }

    /**
     * Load the configuration
     *
     * @param KCommandContext $context	A command context object
     * @return	void
     */
    public function loadConfig(KCommandContext $context)
    {
        // Check if the site exists
        if($this->getService('com://admin/sites.model.sites')->getList()->find($this->getSite()))
        {
            //Load the application config settings
            JFactory::getConfig()->loadArray($this->_options->toArray());

            //Load the global config settings
            require_once( $this->_options->config_file );
            JFactory::getConfig()->loadObject(new JConfig());

            //Load the site config settings
            require_once( JPATH_SITES.'/'.$this->getSite().'/settings.php');
            JFactory::getConfig()->loadObject(new JSettings());
        }
        else throw new KException('Site :'.$this->getSite().' not found', KHttpResponse::NOT_FOUND);
    }

    /**
     * Load the user session or create a new one
     *
     * Old sessions are flushed based on the configuration value for the cookie lifetime. If an existing session, then
     * the last access time is updated. If a new session, a session id is generated and a record is created in the
     * #__users_sessions table.
     *
     * @param KCommandContext $context	A command context object
     * @return	void
     */
    public function loadSession( KCommandContext $context )
    {
        $config = array(
            'name'     => md5($this->getCfg('secret').$this->getCfg('session_name')),
            'lifetime' => $this->getCfg('config.lifetime', 15) * 60,
            'handler'  => 'database',
            'table'    => 'com://admin/users.database.table.sessions',
            'options'  => array(
                'cookie_secure' => $this->getCfg('force_ssl') == 2 ? true : false
            )
        );

        //Create the session
        $session = $this->getService('application.session', $config);

        //Auto-start the session if a cookie is found
        if(!$session->isActive())
        {
            if (KRequest::has('cookie.'.$session->getName())) {
                $session->start();
            }
        }

        //Fork the session if we changed sites
        if(!JFactory::getUser()->guest && ($session->site != $this->getSite()))
        {
            if(!$this->getService('com://admin/users.controller.session')->fork()) {
                $session->destroy();
            }
        }
    }

    /**
     * Get the application languages.
     *
     * @return	\ComLanguagesDatabaseRowsetLanguages
     */
    public function loadLanguage(KCommandContext $context)
    {
        $languages = $this->getService('application.languages');
        $language = null;

        // If a language was specified it has priority.
        if($iso_code = $this->_options->language)
        {
            $result = $languages->find(array('iso_code' => $iso_code));
            if(count($result) == 1) {
                $language = $result->top();
            }
        }

        // Otherwise use user language setting.
        if(!$language && $iso_code = JFactory::getUser()->getParam('language'))
        {
            $result = $languages->find(array('iso_code' => $iso_code));
            if(count($result) == 1) {
                $language = $result->top();
            }
        }

        // If language still not set, use the primary.
        if(!$language) {
            $language = $languages->getPrimary();
        }

        $languages->setActive($language);

        // TODO: Remove this.
        JFactory::getConfig()->setValue('config.language', $language->iso_code);

        return $this->_languages;
    }

    /**
     * Get the application router.
     *
     * @param  array $options 	An optional associative array of configuration options.
     * @return	\ComApplicationRouter
     */
    public function getRouter(array $options = array())
    {
        $router = $this->getService('com://admin/application.router', $options);
        return $router;
    }

    /**
     * Gets a configuration value.
     *
     * @param	string	$name    The name of the value to get.
     * @param	mixed	$default The default value
     * @return	mixed	The user state.
     */
    public function getCfg( $name, $default = null )
    {
        return JFactory::getConfig()->getValue('config.' . $name, $default);
    }

    /**
     * Gets the name of site
     *
     * @return	string
     */
    public function getSite()
    {
        return $this->_site;
    }

    /**
     * Get the template
     *
     * @return string The template name
     */
    public function getTemplate()
    {
        return 'default';
    }

    /**
     * Redirect to another URL.
     *
     * Optionally enqueues a message in the system message queue (which will be displayed
     * the next time a page is loaded) using the enqueueMessage method. If the headers have
     * not been sent the redirect will be accomplished using a "301 Moved Permanently" or "303 See Other"
     * code in the header pointing to the new location depending upon the moved flag. If the headers
     * have already been sent this will be accomplished using a JavaScript statement.
     *
     * @param	string	$url	The URL to redirect to. Can only be http/https URL
     * @param	string	$msg	An optional message to display on redirect.
     * @param	string  $msgType An optional message type.
     * @param	boolean	True if the page is 301 Permanently Moved, otherwise 303 See Other is assumed.
     * @return	none; calls exit().
     */
    function redirect( $url, $msg = '', $msgType = 'message', $moved = false )
    {
        // check for relative internal links
        if (preg_match( '#^index[2]?.php#', $url )) {
            $url = JURI::base() . $url;
        }

        // Strip out any line breaks
        $url = preg_split("/[\r\n]/", $url);
        $url = $url[0];

        // If we don't start with a http we need to fix this before we proceed
        // We could validly start with something else (e.g. ftp), though this would
        // be unlikely and isn't supported by this API
        if(!preg_match( '#^http#i', $url ))
        {
            $uri = JURI::getInstance();
            $prefix = $uri->toString(Array('scheme', 'user', 'pass', 'host', 'port'));

            if($url[0] == '/')
            {
                // we just need the prefix since we have a path relative to the root
                $url = $prefix . $url;
            }
            else
            {
                // its relative to where we are now, so lets add that
                $parts = explode('/', $uri->toString(Array('path')));
                array_pop($parts);
                $path = implode('/',$parts).'/';
                $url = $prefix . $path . $url;
            }
        }


        // If the message exists, enqueue it
        if (trim( $msg )) {
            $this->enqueueMessage($msg, $msgType);
        }

        // Persist messages if they exist
        if (count($this->_messageQueue))
        {
            $session = JFactory::getSession();
            $session->set('application.queue', $this->_messageQueue);
        }

        // If the headers have been sent, then we cannot send an additional location header
        // so we will output a javascript redirect statement.
        if (headers_sent()) {
            echo "<script>document.location.href='$url';</script>\n";
        } else {
            header($moved ? KRequest::protocol().' 301 Moved Permanently' : KRequest::protocol().' 303 See other');
            header('Location: '.$url);
        }

        exit(0);
    }

    /**
     * Enqueue a system message.
     *
     * @param	string 	$msg 	The message to enqueue.
     * @param	string	$type	The message type.
     */
    function enqueueMessage( $msg, $type = 'message' )
    {
        // For empty queue, if messages exists in the session, enqueue them first
        if (!count($this->_message_queue))
        {
            $session = JFactory::getSession();
            $session_queue = $session->get('application.queue');

            if (count($session_queue))
            {
                $this->_message_queue = $session_queue;
                $session->set('application.queue', null);
            }
        }

        // Enqueue the message
        $this->_message_queue[] = array('message' => $msg, 'type' => strtolower($type));
    }

    /**
     * Get the system message queue.
     *
     * @return	The system message queue.
     */
    function getMessageQueue()
    {
        // For empty queue, if messages exists in the session, enqueue them
        if (!count($this->_message_queue))
        {
            $session = JFactory::getSession();
            $session_queue = $session->get('application.queue');

            if (count($session_queue))
            {
                $this->_message_queue = $session_queue;
                $session->set('application.queue', null);
            }
        }

        return $this->_message_queue;
    }

    /**
     * Find the site name
     *
     * This function tries to get the site name based on the information present in the request. If no site can be found
     * it will return 'default'.
     *
     * @return string   The site name
     */
    protected function _findSite()
    {
        // Check URL host
        $uri  = clone(JURI::getInstance());
        $site = 'default';

        $host = $uri->getHost();
        if(!$this->getService('com://admin/sites.model.sites')->getList()->find($host))
        {
            // Check request
            $method = strtolower(KRequest::method());

            if(KRequest::has($method.'.site'))
            {
                $request = KRequest::get($method.'.site', 'cmd');
                if($this->getService('com://admin/sites.model.sites')->getList()->find($request)) {
                    $site = $request;
                }
            }

        } else $site = $host;

        return $site;
    }
}
