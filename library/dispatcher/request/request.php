<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Request
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Abstract Dispatcher Request Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Request
 */
class DispatcherRequest extends ControllerRequest implements DispatcherRequestInterface, ObjectInstantiable
{
    /**
     * The request cookies
     *
     * @var HttpMessageParameters
     */
    protected $_cookies;

    /**
     * The request files
     *
     * @var HttpMessageParameters
     */
    protected $_files;

    /**
     * Base url of the request.
     *
     * @var HttpUrl
     */
    protected $_base_url;

    /**
     * Base path of the request.
     *
     * @var string
     */
    protected $_base_path;

    /**
     * Root url of the request.
     *
     * @var HttpUrl
     */
    protected $_root;

    /**
     * Referrer of the request
     *
     * @var HttpUrl
     */
    protected $_referrer;

    /**
     * The format
     *
     * @var string
     */
    protected $_format;

    /**
     * The token
     *
     * @var string
     */
    protected $_token;

    /**
     * The supported languages
     *
     * @var array
     */
    protected $_languages;

    /**
     * The supported charsets
     *
     * @var array
     */
    protected $_charsets;

    /**
     * Mimetype to format mappings
     *
     * @var array
     */
    protected static $_formats;

    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return DispatcherRequest
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set files parameters
        $this->setFiles($config->files);

        //Set cookie parameters
        $this->setCookies($config->cookies);

        //Set the base URL
        $this->setBaseUrl($config->base_url);

        //Set the base path
        $this->setBasePath($config->base_path);

        //Set the formats
        foreach($config->formats as $format => $mimetypes) {
            $this->addFormat($format, $mimetypes);
        }

        //Set document root for IIS
        if(!isset($_SERVER['DOCUMENT_ROOT']))
        {
            if(isset($_SERVER['SCRIPT_FILENAME'])) {
                $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
            }

            if(isset($_SERVER['PATH_TRANSLATED'])) {
                $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
            }
         }

        //Set the authorization
        if (!isset($_SERVER['PHP_AUTH_USER']))
        {
            /*
             * If you are running PHP as CGI. Apache does not pass HTTP Basic user/pass to PHP by default.
             * To fix this add these lines to your .htaccess file:
             *
             * RewriteCond %{HTTP:Authorization} ^(.+)$
             * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
             */

            //When using PHP-FPM HTTP_AUTHORIZATION is called REDIRECT_HTTP_AUTHORIZATION
            if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }

            // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
            if (isset($_SERVER['HTTP_AUTHORIZATION']) && stripos($_SERVER['HTTP_AUTHORIZATION'], 'basic') === 0)
            {
                $exploded = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'] , 6)));
                if (count($exploded) == 2) {
                    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = $exploded;
                }
            }
        }

        //Set the headers
        $headers = array();
        foreach ($_SERVER as $key => $value)
        {
            if ($value && strpos($key, 'HTTP_') === 0)
            {
                // Cookies are handled using the $_COOKIE superglobal
                if (strpos($key, 'HTTP_COOKIE') === 0) {
                    continue;
                }

                $headers[substr($key, 5)] = $value;
            }
            elseif ($value && strpos($key, 'CONTENT_') === 0)
            {
                $name = substr($key, 8); // Content-
                $name = 'Content-' . (($name == 'MD5') ? $name : ucfirst(strtolower($name)));

                $headers[$name] = $value;
            }
        }

        if(isset($_SERVER['PHP_AUTH_USER']))
        {
            $headers['PHP_AUTH_USER'] = $_SERVER['PHP_AUTH_USER'];
            $headers['PHP_AUTH_PW']   = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
        }

        $this->_headers->add($headers);

        //Set the version
        if (isset($_SERVER['SERVER_PROTOCOL']) && strpos($_SERVER['SERVER_PROTOCOL'], '1.0') !== false) {
            $this->setVersion('1.0');
        }

        //Set request data
        if($this->getContentType() == 'application/x-www-form-urlencoded')
        {
            if (in_array($this->getMethod(), array('PUT', 'DELETE', 'PATCH')))
            {
                parse_str($this->getContent(), $data);
                $this->data->add($data);
            }
        }

        if($this->getContentType() == 'application/json')
        {
            if(in_array($this->getMethod(), array('POST', 'PUT', 'DELETE', 'PATCH')))
            {
                $data = json_decode($this->getContent(), true);
                $this->data->add($data);
            }
        }
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'base_url'  => '/',
            'base_path' => null,
            'method'   => null,
            'formats'  => array(
                'html'   => array('text/html', 'application/xhtml+xml'),
                'txt'    => array('text/plain'),
                'js'     => array('application/javascript', 'application/x-javascript', 'text/javascript'),
                'css'    => array('text/css'),
                'json'   => array('application/json', 'application/x-json'),
                'xml'    => array('text/xml', 'application/xml', 'application/x-xml'),
                'rdf'    => array('application/rdf+xml'),
                'atom'   => array('application/atom+xml'),
                'rss'    => array('application/rss+xml'),
                'stream' => array('application/stream+json'),
            ),
            'query'   => $_GET,
            'data'    => $_POST,
            'cookies' => $_COOKIE,
            'files'   => $_FILES
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return DispatcherRequest
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        if (!$manager->isRegistered('request'))
        {
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->registerAlias('request', $config->object_identifier);
        }

        return $manager->getObject('request');
    }

    /**
     * Set the request cookies
     *
     * @param  array $cookies
     * @return DispatcherRequestInterface
     */
    public function setCookies($parameters)
    {
        $this->_cookies = $this->getObject('lib:http.message.parameters', array('parameters' => $parameters));
    }

    /**
     * Get the request cookies
     *
     * @return HttpMessageParameters
     */
    public function getCookies()
    {
        return $this->_cookies;
    }

    /**
     * Set the request files
     *
     * @param  array $files
     * @return DispatcherRequestInterface
     */
    public function setFiles($parameters)
    {
        $this->_files = $this->getObject('lib:http.message.parameters', array('parameters' => $parameters));
    }

    /**
     * Get the request files
     *
     * @return HttpMessageParameters
     */
    public function getFiles()
    {
        return $this->_files;
    }

    /**
     * Returns current request method.
     *
     * @return  string
     */
    public function getMethod()
    {
        if(!isset($this->_method) && isset($_SERVER['REQUEST_METHOD']))
        {
            $method = strtoupper($_SERVER['REQUEST_METHOD']);

            if($method == 'POST')
            {
                if($this->_headers->has('X-Http-Method-Override')) {
                    $method = strtoupper($this->_headers->get('X-Http-Method-Override'));
                }

                if($this->data->has('_method')) {
                    $method = strtoupper($this->data->get('_method', 'alpha'));
                }
            }

            $this->_method = $method;
        }

        return $this->_method;
    }

    /**
     * Sets the request method.
     *
     * @param string $method
     * @return DispatcherRequest
     */
    public function setMethod($method)
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        return parent::setMethod($method);
    }

    /**
     * Get the POST or PUT raw content information
     *
     * The raw post data is not available with enctype="multipart/form-data".
     *
     * @return  string  The content data
     */
    public function getContent()
    {
        if (!isset($this->_content) && $this->_headers->has('Content-Length') && $this->_headers->get('Content-Length') > 0)
        {
            $data = '';

            $input = fopen('php://input', 'r');
            while ($chunk = fread($input, 1024)) {
                $data .= $chunk;
            }

            fclose($input);

            $this->_content = $data;
        }

        return $this->_content;
    }

    /**
     * Get the POST or PUT content type
     *
     * @return  string   The content type
     */
    public function getContentType()
    {
        if (empty($this->_content_type) && $this->_headers->has('Content-Type'))
        {
            $type = $this->_headers->get('Content-Type');

            // strip parameters from content-type like "; charset=UTF-8"
            if (is_string($type))
            {
                if (preg_match('/^([^,\;]*)/', $type, $matches)) {
                    $type = $matches[1];
                }
            }

            $this->_content_type = $type;
        }

        return $this->_content_type;
    }

    /**
     * Return the URI of the request regardless of the server
     *
     * @return  HttpUrl    A HttpUri object
     */
    public function getUrl()
    {
        if(!isset($this->_url))
        {
            //Scheme
            $url = $this->isSecure() ? 'https://' : 'http://';

            if (!$host = $this->_headers->get('Host'))
            {
                if (!isset($_SERVER['SERVER_NAME'])) {
                    $host = $this->getAddress();
                } else {
                    $host = $_SERVER['SERVER_NAME'];
                }
            }

            // Remove port number from host
            $host = preg_replace('/:\d+$/', '', $host);

            // host is lowercase as per RFC 952/2181
            $host = trim(strtolower($host));

            /*
             * Since we are assigning the URI from the server variables, we first need to determine if we
             * are running on apache or IIS.  If PHP_SELF and REQUEST_URI are present, we will assume we
             * are running on apache.
             */
            if (!empty ($_SERVER['PHP_SELF']) && !empty ($_SERVER['REQUEST_URI']))
            {
                //Prepend the protocol, and the http host to the URI string.
                $url .= $host . $_SERVER['REQUEST_URI'];
            }
            else
            {
                /*
                 * Since we do not have REQUEST_URI to work with, we will assume we are running on IIS
                 * and will therefore need to work some magic with the SCRIPT_NAME and QUERY_STRING
                 * environment variables.
                 */

                // IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable
                $url .= $host . $_SERVER['SCRIPT_NAME'];

                // If the query string exists append it to the URI string
                if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
                    $url .= '?' . $_SERVER['QUERY_STRING'];
                }
            }

            // Sanitize the url since we can't trust the server var
            $url = $this->getObject('lib:filter.url')->sanitize($url);

            // Create the URI object
            $this->_url = $this->getObject('lib:http.url', array('url' => $url));

            //Set the url port
            $port = $_SERVER['SERVER_PORT'];

            if (($this->_url->scheme == 'http' && $port != 80) || ($this->_url->scheme == 'https' && $port != 443)) {
                $this->_url->port = $port;
            }

            //Set the user
            if($this->_headers->has('PHP_AUTH_USER'))
            {
                $this->_url->user = $this->_headers->get('PHP_AUTH_USER');

                if($this->_headers->has('PHP_AUTH_PW')) {
                    $this->_url->pass = $this->_headers->get('PHP_AUTH_PASS');
                }
            }
        }

        return $this->_url;
    }

    /**
     * Returns the HTTP referrer.
     *
     * 'referer' a commonly used misspelling word for 'referrer'
     * @see     http://en.wikipedia.org/wiki/HTTP_referrer
     *
     * @param   boolean  $isInternal Only allow internal url's
     * @return  HttpUrl  A HttpUrl object
     */
    public function getReferrer($isInternal = true)
    {
        if(!isset($this->_referrer))
        {
            $referrer = $this->getObject('lib:filter.url')->sanitize($this->_headers->get('Referer'));
            $this->_referrer = $this->getObject('lib:http.url', array('url' => $referrer));
        }

        if($isInternal)
        {
            if(!$this->getObject('lib:filter.internalurl')->validate($this->_referrer->toString(HttpUrl::SCHEME | HttpUrl::HOST))) {
                return null;
            }
        }

        return $this->_referrer;
    }

    /**
     * Returns the agent who made the request
     *
     * @return string $_SERVER['HTTP_USER_AGENT'] or an empty string if it's not supplied in the request
     */
    public function getAgent()
    {
        return $this->_headers->get('User-Agent', '');
    }

    /**
     * Returns the client IP address.
     *
     * @return string $_SERVER['REMOTE_ADDR'] or an empty string if it's not supplied in the request
     */
    public function getAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Returns the base URL from which this request is executed.
     *
     * The base URL never ends with a / and t also includes the script filename (e.g. index.php) if one exists.
     *
     * Suppose this request is instantiated from /mysite on localhost:
     *
     *  * http://localhost/mysite              returns an empty string
     *  * http://localhost/mysite/about        returns '/about'
     *  * http://localhost/mysite/enco%20ded   returns '/enco%20ded'
     *  * http://localhost/mysite/about?var=1  returns '/about'
     *
     * @return  object  A HttpUrl object
     */
    public function getBaseUrl()
    {
        if(!$this->_base_url instanceof HttpUrl)
        {
            $base = clone $this->getUrl();
            $base->fromString(rtrim((string)$this->_base_url, '/'));

            $this->_base_url = $this->getObject('lib:http.url', array('url' => $base->toString(HttpUrl::BASE)));
        }

        return $this->_base_url;
    }

    /**
     * Set the base URL for which the request is executed.
     *
     * @param string $url
     * @return DispatcherRequest
     */
    public function setBaseUrl($url)
    {
        $this->_base_url = $url;
        return $this;
    }

    /**
     * Returns the base path of the request.
     *
     * @param   boolean  If TRUE create a fully qualified path. Default TRUE.
     * @return  string
     */
    public function getBasePath($fqp = false)
    {
        if(!isset($this->_base_path))
        {
            // PHP-CGI on Apache with "cgi.fix_pathinfo = 0". We don't have user-supplied PATH_INFO in PHP_SELF
            if (strpos(PHP_SAPI, 'cgi') !== false && !ini_get('cgi.fix_pathinfo')  && !empty($_SERVER['REQUEST_URI'])) {
                $path = $_SERVER['PHP_SELF'];
            } else {
                $path = $_SERVER['SCRIPT_NAME'];
            }

            $this->_base_path = rtrim(dirname($path), '/\\');
        }

        return $fqp ? $_SERVER['DOCUMENT_ROOT'].$this->_base_path : $this->_base_path;
    }

    /**
     * Set the base path for which the request is executed.
     *
     * @param string $path
     * @return DispatcherRequest
     */
    public function setBasePath($path)
    {
        $this->_base_path = $path;
        return $this;
    }

    /**
     * Return the request token
     *
     * @return  string  The request token or NULL if no token could be found
     */
    public function getToken()
    {
        if(!isset($this->_token))
        {
            $token = null;

            if($this->_headers->has('X-Token')) {
                $token = $this->_headers->get('X-Token');
            }

            if($this->data->has('_token')) {
                $token = $this->data->get('_token', 'md5');
            }

            $this->_token = $token;
        }

        return $this->_token;
    }

    /**
     * Return the request format
     *
     * This function tries to find the format by inspecting the accept header and using the accept header with the
     * highest quality. The accept mime-type will be mapped to a format. If the request query contains a 'format'
     * parameter it will be used instead.
     *
     * @param string $format The default format
     * @return  string  The request format or NULL if no format could be found
     */
    public function getFormat($format = 'html')
    {
        if (!isset($this->_format))
        {
            if(!$this->query->has('format'))
            {
                if($this->_headers->has('Accept'))
                {
                    $accept  = $this->_headers->get('Accept');
                    $formats = $this->_parseAccept($accept);

                    //Get the highest quality format
                    $mime_type = key($formats);

                    foreach (static::$_formats as $value => $mime_types)
                    {
                        if (in_array($mime_type, (array) $mime_types))
                        {
                            $format = $value;
                            break;
                        }
                    }
                }
            }
            else $format = $this->query->get('format', 'word');

            $this->_format = $format;
        }

        return $this->_format;
    }

    /**
     * Associates a format with mime types.
     *
     * @param string       $format    The format
     * @param string|array $mimeTypes The associated mime types (the preferred one must be the first as it will be used
     *                                as the content type)
     * @return DispatcherRequest
     */
    public function addFormat($format, $mime_types)
    {
        static::$_formats[$format] = is_array($mime_types) ? $mime_types : array($mime_types);
        return $this;
    }

    /**
     * Gets a list of languages acceptable by the client browser.
     *
     * @return array Languages ordered in the user browser preferences
     */
    public function getLanguages()
    {
        if (!isset($this->languages))
        {
            $this->_languages = array();

            if($this->_headers->has('Accept-Language'))
            {
                $accept    = $this->_headers->get('Accept-Language');
                $languages = $this->_parseAccept($accept);

                foreach (array_keys($languages) as $lang)
                {
                    if (strstr($lang, '-'))
                    {
                        $codes = explode('-', $lang);
                        if ($codes[0] == 'i')
                        {
                            // Language not listed in ISO 639 that are not variants
                            // of any listed language, which can be registered with the
                            // i-prefix, such as i-cherokee
                            if (count($codes) > 1) {
                                $lang = $codes[1];
                            }
                        }
                        else
                        {
                            for ($i = 0, $max = count($codes); $i < $max; $i++)
                            {
                                if ($i == 0) {
                                    $lang = strtolower($codes[0]);
                                } else {
                                    $lang .= '_'.strtoupper($codes[$i]);
                                }
                            }
                        }
                    }

                    $this->_languages[] = $lang;
                }
            }
        }

        return $this->_languages;
    }

    /**
     * Gets a list of charsets acceptable by the client browser.
     *
     * @return array List of charsets in preferable order
     */
    public function getCharsets()
    {
        if (!isset($this->_charsets))
        {
            $this->_charsets = array();

            if($this->_headers->has('Accept-Charset'))
            {
                $accept   = $this->_headers->get('Accept-Charset');
                $charsets = $this->_parseAccept($accept);

                $this->_charsets = array_keys($charsets);
            }
        }

        return $this->_charsets;
    }

    /**
     * Is this a POST method request?
     *
     * @return bool
     */
    /*public function isPost()
    {
        return (strtoupper($_SERVER['REQUEST_METHOD']) === self::POST);
    }*/

    /**
     * Checks whether the request is secure or not.
     *
     * @return  string
     */
    public function isSecure()
    {
        return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off');
    }

    /**
     * Implement a virtual 'headers', 'query' and 'data class property to return their respective objects.
     *
     * @param   string $name  The property name.
     * @return  string $value The property value.
     */
    public function __get($name)
    {
        if($name == 'cookies') {
            return $this->getCookies();
        }

        if($name == 'files') {
            return $this->getFiles();
        }

        return parent::__get($name);
    }

    /**
     * Parses an accept header and returns an array (type => quality) of the accepted types,
     * ordered by quality.
     *
     * @param string    $accept     The header to parse
     * @param array     $default    The default values
     * @return array
     */
    protected function _parseAccept( $accept, array $defaults = NULL)
    {
        if (!empty($accept))
        {
            // Get all of the types
            $types = explode(',', $accept);

            foreach ($types as $type)
            {
                // Split the type into parts
                $parts = explode(';', $type);

                // Make the type only the MIME
                $type = trim(array_shift($parts));

                // Default quality is 1.0
                $options = array('quality' => 1.0);

                foreach ($parts as $part)
                {
                    // Prevent undefined $value notice below
                    if (strpos($part, '=') === FALSE) {
                        continue;
                    }

                    // Separate the key and value
                    list ($key, $value) = explode('=', trim($part));

                    switch ($key)
                    {
                        case 'q'       : $options['quality'] = (float) trim($value); break;
                        case 'version' : $options['version'] = (float) trim($value); break;
                    }
                }

                // Add the accept type and quality
                $defaults[$type] = $options;
            }
        }

        // Make sure that accepts is an array
        $accepts = (array) $defaults;

        // Order by quality
        arsort($accepts);

        return $accepts;
    }

    /**
     * Deep clone of this instance
     *
     * @return void
     */
    public function __clone()
    {
        parent::__clone();

        $this->_cookies = clone $this->_cookies;
        $this->_files   = clone $this->_files;
    }
}