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
 * Varnish CLI Controller
 *
 * With tags you can group related representations so it becomes easier to invalidate them. You will have to make sure
 * your application adds the correct tags on all responses.
 *
 * Tagging is done through the response while banning is done using the VarnishAdm CLI interface. This controller sets
 * up a socket connection with the VarnishAdm cli service.
 *
 * @link : https://www.varnish-cache.org/docs/3.0/reference/varnishadm.html
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Varnish
 */
class ControllerCache extends Library\ControllerAbstract implements Library\ObjectMultiton
{
    const SOCKET_TIMEOUT = 5;

    /**
     * Varnish constants, as defined in include/vcli.h
     */
    const CLIS_SYNTAX	= 100;
    const CLIS_UNKNOWN	= 101;
    const CLIS_UNIMPL	= 102;
    const CLIS_TOOFEW	= 104;
    const CLIS_TOOMANY	= 105;
    const CLIS_PARAM	= 106;
    const CLIS_AUTH		= 107;
    const CLIS_OK		= 200;
    const CLIS_CANT		= 300;
    const CLIS_COMMS	= 400;
    const CLIS_CLOSE	= 500;

    /**
     * The proxy server host
     *
     * @var string $host
     */
    protected $_host;

    /**
     * The proxy server port
     *
     * @var integer $port
     */
    protected $_port;

    /**
     * The proxy server secret
     *
     * @var string $secret
     */
    protected $_secret;

    /**
     * Debug enabled
     *
     * @var boolean
     */
    protected $_debug;

    /**
     * ESI enabled
     *
     * @var boolean
     */
    protected $_esi;

    /**
     * The name of the header used for tagging
     *
     * @var string
     */
    protected $_header;

    /**
     * The connection
     *
     * @var resource $connection
     */
    private $__connection;

    /**
     * Enabled status of the cache
     *
     * @var boolean
     */
    private $__enabled;

    /**
     * Constructor.
     *
     * @param string $host The host name/IP address of the Varnish server to connect to
     * @param integer $port The port number to connect to Varnish on
     * @param string $secret The secret to use when authenticating with the server
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_host    = $config->host;
        $this->_port    = $config->port;
        $this->_secret  = $config->secret;
        $this->_header  = $config->header;

        //Auto connect to varnish
        if($config->auto_connect) {
            $this->connect();
        }

        //Set the cache enabled state
        $this->setEnabled($config->enabled);

        //Set the debug state
        $this->setDebug($config->debug);

        //Set the esi state
        $this->setEsi($config->esi);
    }

    /**
     * Destructor
     *
     * Free any resources that are open.
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'host'     => 'localhost',
            'port'     => 6082,
            'secret'   => '',
            'esi'      => false,
            'header'   => 'X-Varnish-Tag',
            'debug'    => \Nooku::getInstance()->isDebug(),
            'enabled'  => true,
            'auto_connect' => true,
        ));

        //Use the dispatcher response in the context
        $config->response = 'dispatcher.response';

        //Use the dispatcher response in the context
        $config->request  = 'dispatcher.request';

        parent::_initialize($config);
    }

    /**
     * Enable the cache
     *
     * @return ControllerCache
     */
    public function setEnabled($enabled)
    {
        $this->__enabled = (bool) $enabled;
        return $this;
    }

    /**
     * Check of the cache is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->__enabled;
    }

    /**
     * Enable or disable debug
     *
     * This method also sets or remove the custom X-Varnish-Debug header. If set Varnish will show whether a cache
     * hit or miss occurred
     *
     * @param bool $debug True or false.
     * @return ControllerCache
     */
    public function setDebug($debug)
    {
        $this->_debug = (bool) $debug;

        if($debug) {
            $this->getResponse()->getHeaders()->set('X-Varnish-Debug', 1);
        } else {
            $this->getResponse()->getHeaders()->remove('X-Varnish-Debug');
        }

        return $this;
    }

    /**
     * Check if debug is enabled
     *
     * @return bool
     */
    public function isDebug()
    {
        return $this->_debug;
    }

    /**
     * Enable or disable esi processing
     *
     * This method also sets or remove the custom Surrogate-Control. If set Varnish will process ESI directives
     * @link : https://www.varnish-cache.org/docs/4.0/users-guide/esi.html
     *
     * @param bool $enable True or false.
     * @return ControllerCache
     */
    public function setEsi($enable)
    {
        $this->_esi = (bool) $enable;

        if($enable) {
            $this->getResponse()->getHeaders()->set('Surrogate-Control', 'content="ESI/1.0"');
        } else {
            $this->getResponse()->getHeaders()->remove('Surrogate-Control');
        }

        return $this;
    }

    /**
     * Check if esi processing is enabled
     *
     * @return bool
     */
    public function canEsi()
    {
        if($this->getRequest()->getHeaders()->has('Surrogate-Capability'))
        {
            $surrogate = $this->getRequest()->getHeaders()->get('Surrogate-Capability');

            if(strpos($surrogate, 'varnish=ESI/1.0') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if esi processing is enabled
     *
     * @return bool
     */
    public function isEsi()
    {
        return $this->_esi;
    }

    /**
     * Connect to the VarnishAdm server.
     *
     * @return ControllerCache
     */
    public function connect()
    {
        if(!$this->__connection)
        {
            $status = null;

            //Attempt to connect to the server.
            if(($this->__connection = fsockopen($this->_host, $this->_port, $errno, $errstr, self::SOCKET_TIMEOUT)) === false) {
                throw new \RuntimeException(sprintf('Failed to connect to VarnishAdm: %s (%d)', $errstr, $errno));
            }

            //Set the socket to be blocking, and set a read/write timeout.
            stream_set_blocking($this->__connection, 1);
            stream_set_timeout($this->__connection, self::SOCKET_TIMEOUT);

            $response = $this->__read($status);
            if( $status === self::CLIS_AUTH )
            {
                $challenge = substr($response, 0, 32);
                $parameter = hash('sha256', sprintf("%s\n%s\n%s\n", $challenge, $this->_secret, $challenge));
                $response  = $this->_command('auth', $parameter, $status);

                if( $status !== self::CLIS_OK ) {
                    throw new \RuntimeException('Failed to authenticate with VarnishAdm.');
                }
            }

            if( $status !== self::CLIS_OK ) {
                throw new \RuntimeException(sprintf('VarnishAdm returned an invalid status code upon connecting: %d.', $status));
            }
        }

        return $this;
    }

    /**
     * Disconnect from the VarnishAdm server.
     *
     * return ControllerCache
     */
    public function disconnect()
    {
        if(is_resource($this->__connection))
        {
            $this->_command('quit', null);
            fclose($this->__connection);
        }

        $this->__connection = null;

        return $this;
    }

    /**
     * Issue a command to VarnishAdm.
     *
     * @param string    $command    The command to issue
     * @param string    $parameter  The optional parameter to issue along with the command
     * @param integer   $status     The status code of the response, passed by reference
     * @return string The server's response
     */
    protected function _command($command, $parameter, &$status)
    {
        if( $parameter === null || $parameter === '' ) {
            $this->__write($command . "\n");
        } else {
            $this->__write(sprintf("%s %s\n", $command, $parameter));
        }

        return $this->__read($status);
    }

    /**
     * Tag the response
     *
     * @param  Library\ControllerContextInterface	$context    A controller context object
     * @return bool TRUE if tagging was succesful or FALSE of the cache is disabled
     */
    protected function _actionTag($context)
    {
        if($this->isEnabled())
        {
            $name  = $this->_header;
            $value = Library\ControllerContext::unbox($context->param);

            //Add the tag and do not replace the header.
            $context->response->headers->set($name, $value, false);

            return true;
        }

        return false;
    }

    /**
     * Ban cached objects by tag
     *
     * @param  Library\ControllerContextInterface	$context    A controller context object
     * @return string|false The server's response or FALSE of the cache is disabled
     */
    protected function _actionBan($context)
    {
        if($this->isEnabled())
        {
            $status    = null;
            $parameter = sprintf('obj.http.'.strtolower($this->_header).' == %s', Library\ControllerContext::unbox($context->param));

            $response = $this->_command('ban', $parameter, $status);
            if( $status !== self::CLIS_OK ) {
                throw new \RuntimeException(sprintf('VarnishAdm failed to ban condition "%s" (status: %d).', $parameter, $status));
            }

            return $response;
        }

        return false;
    }


    /**
     * Read data from the socket.
     *
     * @param integer $status The status code of the response, passed by reference
     * @return string The response text
     */
    private function __read(&$status)
    {
        $status = null;
        $length = null;

        // Attempt to find the header line, which contains the status code and the length of the response.
        while( !feof($this->__connection) )
        {
            if( ($response = fgets($this->__connection, 1024)) === false )
            {
                $metadata = stream_get_meta_data($this->__connection);
                if( $metadata['timed_out'] ) {
                    throw new \RuntimeException('Timed out reading from VarnishAdm socket.');
                }

                throw new \RuntimeException('Failed to read from VarnishAdm socket.');
            }

            if( strlen($response) === 13 && preg_match('/^(\d{3}) (\d+)/', $response, $matches) )
            {
                $status = (int)$matches[1];
                $length = (int)$matches[2];
                break;
            }
        }

        if( $status === null ) {
            throw new \RuntimeException('VarnishAdm failed to return a valid status code.');
        }

        //Retrieve the response.
        $response = '';
        while( !feof($this->__connection) && strlen($response) < $length ) {
            $response .= fread($this->__connection, $length);
        }

        if( strlen($response) !== $length ) {
            throw new \RuntimeException('VarnishAdm failed to return a valid response.');
        }

        return $response;
    }

    /**
     * Write data to the socket.
     *
     * @param string $data The data to write to the socket
     */
    private function __write($data)
    {
        if( ($bytes = fwrite($this->__connection, $data)) !== strlen($data) )
        {
            throw new \RuntimeException(
                sprintf('Failed to write data to VarnishAdm socket. Expected %d bytes, wrote %d bytes.', strlen($data), $bytes)
            );
        }
    }
}