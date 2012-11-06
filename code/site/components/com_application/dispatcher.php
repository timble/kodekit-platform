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
     * The pathway object
     *
     * @var object
     */
    protected $_pathway = null;

    /**
     * Constructor.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Register the default error handler
        $this->getService('application.debug')->addEventListener('onError', array($this, 'error'));

        $this->registerCallback('before.run', array($this, 'loadConfig'));
        $this->registerCallback('before.run', array($this, 'loadSession'));
        $this->registerCallback('before.run', array($this, 'loadLanguage'));

        // Set the connection options
        $this->_options = $config->options;

        //Setup the request
        KRequest::root(str_replace('/site', '', KRequest::base()));

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
                'session_name' => 'site',
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

        //Set the site error reporting
        $this->getService('application.debug')->setDebugMode($this->getCfg('debug_mode'));

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
        $url   = clone KRequest::url();
        $pages = $this->getService('application.pages');

        if(KRequest::type() != 'AJAX')
        {
            // get the route based on the path
            $route = trim(str_replace(array(KRequest::base()->getPath(), $this->getSite(), 'index.php'), '', $url->getPath()), '/');

            //Redirect to the default menu item if the route is empty
            if(empty($route))
            {
                $url = $pages->getHome()->link;
                $url->query['Itemid'] = $pages->getHome()->id;

                $this->getRouter()->build($url);

                return $context->response->setRedirect($url, KHttpResponse::MOVED_PERMANENTLY);
            }
        }

        //Parse the route
        $this->getRouter()->parse($url);
        
        // Redirect if page type is redirect.
        if(($page = $pages->getActive()) && $page->type == 'redirect')
        {
            if($page->link_id)
            {
                $redirect = $pages->getPage($page->link_id);
                $url      = $redirect->type == 'url' ? $redirect->link_url : $redirect->route;
            }
            else $url = $page->link_url;

            return $context->response->setRedirect($url, KHttpResponse::MOVED_PERMANENTLY);
        }
        
        //Set the request
        $this->setRequest($url->query);

        //@TODO : Can be removed after refactor of KRequest
        JRequest::set($url->query, 'get');
    }

    protected function _actionAuthorize(KCommandContext $context)
    {
        $user = JFactory::getUser();
        if(!($this->getCfg('offline') && $user->guest))
        {
            if(!$this->getService('application.pages')->isAuthorized($this->getRequest()->Itemid, $user))
            {
                if($user->guest) {
                    $this->option = 'com_users';
                } else {
                    throw new KException(JText::_('ALERTNOTAUTH'), KHttpResponse::FORBIDDEN);
                }
            }
        }
        else $this->option = 'com_users';

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
     */
    protected function _actionDispatch(KCommandContext $context)
    {
        if(!$context->response->isRedirect())
        {
            $component = $this->option;

            if(!empty($component))
            {
                $name = substr( $component, 4);

                if (!$this->getService('application.components')->isEnabled($name)) {
                    return $context->response->setStatus(KHttpResponse::NOT_FOUND, 'Component Not Found');
                }

                //Load the component aliasses
                KLoader::loadIdentifier('com://site/'.$name.'.aliases');

                $this->getService('com://site/'.$name.'.dispatcher')->dispatch($context);

                if(!$context->response->isRedirect()){
                    $this->forward($context);
                }
            }
            else $context->response->setStatus(KHttpResponse::NOT_FOUND, 'Component Not Found');
        }

        $this->send($context);
    }

    /**
     * Render the application
     *
     * Rendering is the process of pushing the document buffers into the template placeholders, retrieving data from the
     * document and pushing it into the response buffer.
     *
     * @param KCommandContext $context	A command context object
     */
    protected function _actionForward(KCommandContext $context)
    {
        $config = array(
            'request' => $this->getRequest(),
        );

        $controller = $this->getService('com://site/application.controller.page', $config);
        $controller->getView()
                   ->option($this->getRequest()->option)
                   ->tmpl(KRequest::get('get.tmpl', 'cmd', 'default'));

        //Render the page controller
        $content = $controller->display($context);

        //Make images paths absolute
        $path = KRequest::root()->getPath().'/'.str_replace(JPATH_ROOT.DS, '', JPATH_IMAGES.'/');

        $content = str_replace(JURI::base().'images/', $path, $content);
        $content = str_replace(array('"images/','"/images/') , '"'.$path, $content);

        $context->response->setContent($content);
    }

    /**
     * Error handler
     *
     * @param KCommandContext $context	A command context object
     */
    protected function _actionError(KCommandContext $context)
    {
        $content = $this->getService('com://admin/application.controller.error')
            ->format(KRequest::format() ? KRequest::format() : 'html')
            ->display($context->data);

        $context->response->setContent($content);
        $context->response->setStatus($context->data->getCode(), $context->data->getMessage());

        $this->send($context);
    }

    /**
     * Send the response to the client
     *
     * @param KCommandContext $context	A command context object
     */
    protected function _actionSend(KCommandContext $context)
    {
        $context->response->send();
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
                'cookie_path'   => (string) KRequest::base(),
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
            if(!$this->getService('com://site/users.controller.session')->fork()) {
                $session->destroy();
            }
        }
    }

    /**
     * Load the application language
     *
     * @param KCommandContext $context	A command context object
     * @return	void
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
        $router = $this->getService('com://site/application.router', $options);
        return $router;
    }

    /**
     * Return a reference to the application JPathway object.
     *
     * @param  array	$options 	An optional associative array of configuration settings.
     * @return object JPathway.
     */
    public function getPathway($options = array())
    {
        if(!isset($this->_pathway))
        {
            require_once(JPATH_APPLICATION.'/includes/pathway.php' );
            $this->_pathway = new JPathway($options);
        }

        return $this->_pathway;
    }

    /**
     * Get the component parameters
     *
     * @param	string	The component option
     * @return	object	The parameters object
     */
    public function getParams($option = null)
    {
        static $params = array();
        $hash = '__default';

        if(!empty($option)) {
            $hash = $option;
        }

        if (!isset($params[$hash]))
        {
            // Get component parameters
            if (!$option) {
                $option = $this->getRequest()->option;
            }

            $params[$hash] = $this->getService('application.components')->getComponent(substr( $option, 4))->params;

            // Get menu parameters
            $page = $this->getService('application.pages')->getActive();

            $title  = htmlspecialchars_decode($this->getCfg('sitename' ));

            // Lets cascade the parameters if we have menu item parameters
            if (is_object($page))
            {
                $params[$hash]->merge(new JParameter((string) $page->params));
                $title = $page->title;

            }

            $params[$hash]->def( 'page_title'      , $title );
            $params[$hash]->def( 'page_description', '' );
        }

        return $params[$hash];
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
        return $this->getCfg('template');
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
            // Check folder
            $path = trim(str_replace(array(JURI::base(true)), '', $uri->getPath()), '/');
            $path = trim(str_replace('index.php', '', $path), '/');

            if(!empty($path))
            {
                $folder = array_shift(explode('/', $path));

                if($this->getService('com://admin/sites.model.sites')->getList()->find($folder)) {
                    $site = $folder;
                }
            }

        } else $site = $host;

        return $site;
    }
}
