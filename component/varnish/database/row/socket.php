<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Socket Database Row
 *
 * @author  Dave Li <http://nooku.assembla.com/profile/daveli>
 * @package Nooku\Component\Varnish
 */
class DatabaseRowSocket extends Library\DatabaseRowAbstract
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
	 * @var string $host
	 */
	private $host;

	/**
	 * @var integer $port
	 */
	private $port;

	/**
	 * @var string $secret
	 */
	private $secret;

	/**
	 * @var resource $connection
	 */
	private $connection;

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

		$this->host		= 'localhost';
		$this->port		= 6082;
		$this->secret	= 'b92f284c-5cc0-461c-9ca9-27e0d2ba9e22';
	}

	/**
	 * Connect to the VarnishAdm server.
	 */
	public function connect()
	{
		/**
		 * There is no point attempting to connect if we are already connected.
		 */
		if( $this->connection !== null )
			return;

		/**
		 * Attempt to connect to the server.
		 */
		if(($this->connection = fsockopen($this->host, $this->port, $errno, $errstr, self::SOCKET_TIMEOUT)) === false) {
			$this->connection = null;
			throw new \RuntimeException(sprintf('Failed to connect to VarnishAdm: %s (%d)', $errstr, $errno));
		}

		/**
		 * Set the socket to be blocking, and set a read/write timeout.
		 */
		stream_set_blocking($this->connection, 1);
		stream_set_timeout($this->connection, self::SOCKET_TIMEOUT);

		$response = $this->read($statusCode);
		if( $statusCode === self::CLIS_AUTH )
			$response = $this->authenticate(substr($response, 0, 32), $statusCode);
		if( $statusCode !== self::CLIS_OK ) {
			$this->disconnect();
			throw new \RuntimeException(sprintf('VarnishAdm returned an invalid status code upon connecting: %d.', $statusCode));
		}
	}

	/**
	 * Disconnect from the VarnishAdm server.
	 */
	public function disconnect()
	{
		if( $this->connection === null )
			return;

		$this->command('quit', null, $statusCode);
		fclose($this->connection);
		$this->connection = null;
	}

	/**
	 * Issue a command to VarnishAdm.
	 *
	 * @param string $command The command to issue
	 * @param string $parameterString The optional parameter string to issue along with the command
	 * @param integer $statusCode The status code of the response, passed by reference
	 * @return string The server's response
	 */
	public function command($command, $parameterString, &$statusCode)
	{
		/**
		 * Issue the command.
		 */
		if( $parameterString === null || $parameterString === '' )
			$this->write($command . "\n");
		else
			$this->write(sprintf("%s %s\n", $command, $parameterString));

		/**
		 * Read and return the response.
		 */
		return $this->read($statusCode);
	}

	/**
	 * Marks all objects that match $condition as obsolete.
	 *
	 * @param string $condition The conditions under which to mark the object as obsolete
	 * @return string The server's response
	 */
	public function ban($condition)
	{
		$response = $this->command('ban', $condition, $statusCode);
		if( $statusCode !== self::CLIS_OK )
			throw new \RuntimeException(sprintf('VarnishAdm failed to ban condition "%s" (status: %d).', $condition, $statusCode));
		return $response;
	}

	/**
	 * Marks all objects that have a URL matching $pattern as obsolete.
	 *
	 * @param string $pattern The pattern to use for matching URLs
	 * @return string The server's response
	 */
	public function ban_url($pattern)
	{
		$response = $this->command('ban.url', $pattern, $statusCode);
		if( $statusCode !== self::CLIS_OK )
			throw new \RuntimeException(sprintf('VarnishAdm failed to ban.url pattern "%s" (status: %d).', $pattern, $statusCode));
		return $response;
	}

	/**
	 * List the active bans.
	 *
	 * @return string The server's response
	 */
	public function ban_list()
	{
		$response = $this->command('ban.list', null, $statusCode);
		if( $statusCode !== self::CLIS_OK )
			throw new \RuntimeException(sprintf('VarnishAdm failed to retrieve the list of active bans (status: %d).', $statusCode));
		return $response;
	}

	/**
	 * Read data from the socket.
	 *
	 * @param integer $statusCode The status code of the response, passed by reference
	 * @return string The response text
	 */
	private function read(&$statusCode)
	{
		$statusCode = null;
		$responseLength = null;

		/**
		 * Attempt to find the header line, which contains the status code and
		 * the length of the response.
		 */
		while( !feof($this->connection) ) {
			if( ($response = fgets($this->connection, 1024)) === false ) {
				$metaData = stream_get_meta_data($this->connection);
				if( $metaData['timed_out'] )
					throw new \RuntimeException('Timed out reading from VarnishAdm socket.');
				throw new \RuntimeException('Failed to read from VarnishAdm socket.');
			}

			if( strlen($response) === 13 && preg_match('/^(\d{3}) (\d+)/', $response, $matches) ) {
				$statusCode = (int)$matches[1];
				$responseLength = (int)$matches[2];
				break;
			}
		}
		if( $statusCode === null )
			throw new \RuntimeException('VarnishAdm failed to return a valid status code.');

		/**
		 * Retrieve the response.
		 */
		$response = '';
		while( !feof($this->connection) && strlen($response) < $responseLength )
			$response .= fread($this->connection, $responseLength);
		if( strlen($response) !== $responseLength )
			throw new \RuntimeException('VarnishAdm failed to return a valid response.');

		/**
		 * Return the response.
		 */
		return $response;
	}

	/**
	 * Write data to the socket.
	 *
	 * @param string $data The data to write to the socket
	 */
	private function write($data)
	{
		if( ($bytesWritten = fwrite($this->connection, $data)) !== strlen($data) )
			throw new \RuntimeException(sprintf('Failed to write data to VarnishAdm socket. Expected %d bytes, wrote %d bytes.', strlen($data), $bytesWritten));
	}

	/**
	 * Perform authentication.
	 *
	 * @param string $challenge The challenge string that the server presented
	 * @param integer $statusCode The status code of the response, passed by reference
	 * @return string The server's response
	 */
	private function authenticate($challenge, &$statusCode)
	{
		$response = hash('sha256', sprintf("%s\n%s\n%s\n", $challenge, $this->secret, $challenge));
		$response = $this->command('auth', $response, $statusCode);
		if( $statusCode !== self::CLIS_OK )
			throw new \RuntimeException('Failed to authenticate with VarnishAdm.');
		return $response;
	}

	/**
	 * Load a configuration file.
	 *
	 * @param $args
	 * @return string
	 * @throws RuntimeException
	 */
	public function vcl_load($args)
	{
		$response = $this->command('vcl.load', $args, $statusCode);
		if( $statusCode !== self::CLIS_OK )
			throw new \RuntimeException(sprintf('VarnishAdm failed to retrieve the list of active bans (status: %d).', $statusCode));
		return $response;
	}

	/**
	 * @param $args
	 * @return string
	 * @throws RuntimeException
	 */
	public function vcl_use($args)
	{
		$response = $this->command('vcl.use', $args, $statusCode);
		if( $statusCode !== self::CLIS_OK )
			throw new \RuntimeException(sprintf('VarnishAdm failed to retrieve the list of active bans (status: %d).', $statusCode));
		return $response;
	}

	/**
	 * @param $args
	 * @return string
	 * @throws RuntimeException
	 */
	public function vcl_discard($args)
	{
		$response = $this->command('vcl.discard', $args, $statusCode);
		if( $statusCode !== self::CLIS_OK )
			throw new \RuntimeException(sprintf('VarnishAdm failed to retrieve the list of active bans (status: %d).', $statusCode));
		return $response;
	}

	/**
	 * @return array
	 * @throws RuntimeException
	 */
	public function vcl_list()
	{
		$response = $this->command('vcl.list', null, $statusCode);
		if( $statusCode !== self::CLIS_OK )
			throw new \RuntimeException(sprintf('VarnishAdm failed to retrieve the list vcl configs (status: %d).', $statusCode));

		$matches = array_filter(preg_split('/[\s\n]/', $response));

		$list = array();

		for($i = 0; $i < count($matches); $i++) {
			if($i % 3 === 0) {
				$pieces = array_slice($matches, $i, 3);
				$list[] = array(
					'name'		=> end($pieces),
					'status'	=> reset($pieces)
				);
			}
		}

		return $list;
	}
}