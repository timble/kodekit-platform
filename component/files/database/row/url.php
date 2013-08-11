<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Url Database Row
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class DatabaseRowUrl extends Library\DatabaseRowAbstract
{
	/**
	 * Adapters to use for remote access
     *
	 * @var array
	 */
	protected $_adapters = array();

	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		if (isset($config->adapters)) {
			$this->_adapters = $config->adapters;
		}
	}

	protected function _initialize(Library\ObjectConfig $config)
	{
		if (empty($config->adapters)) {
			$config->adapters = array('curl', 'fsockopen', 'fopen');
		}
		elseif (is_string($config->adapters)) {
			$config->adapters = array($config->adapters);
		}

		parent::_initialize($config);
	}

	public function load()
	{
		$url = $this->file;
		$response = $this->_fetch($url);

		if ($response === false) {
			throw new DatabaseRowUrlException('File cannot be downloaded');
		}

		$this->contents = $response;

		return true;
	}

	protected function _fetch($url)
	{
		$response = false;
		foreach ($this->_adapters as $i => $adapter)
		{
			try {
				$function = '_fetch'.ucfirst($adapter);
				$response = $this->$function($url);
				break;
			}
			catch (DatabaseRowUrlAdapterException $e) {
				continue;
			}
			catch (DatabaseRowUrlException $e)
			{
				if ($i+1 < count($this->_adapters)) {
					continue;
				}
				else {
					throw $e;
				}
			}
		}

		return $response;
	}

	protected function _fetchCurl($url)
	{
		if (!function_exists('curl_init')) {
			throw new DatabaseRowUrlAdapterException('Adapter does not exist');
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS,		 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 		 120);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			throw new DatabaseRowUrlException('Curl Error: '.curl_error($ch));
		}

		$info = curl_getinfo($ch);
		if (isset($info['http_code']) && $info['http_code'] != 200) {
			$response = false;
		}

		curl_close($ch);

		return $response;
	}

	protected function _fetchFsockopen($url)
	{
		if (!in_array('tcp', stream_get_transports())) {
			throw new DatabaseRowUrlAdapterException('Adapter does not exist');
		}

		$uri = $this->getObject('lib:http.url', array('url' => $url));

		$scheme = $uri->toString(Library\HttpUrl::SCHEME);
		$host = $uri->toString(Library\HttpUrl::HOST);
		$port = $uri->toString(Library\HttpUrl::PORT);
		$path = $uri->toString(Library\HttpUrl::PATH | Library\HttpUrl::FORMAT | Library\HttpUrl::QUERY | Library\HttpUrl::FRAGMENT);

		if ($scheme == 'https://')
        {
			if (!in_array('ssl', stream_get_transports())) {
				throw new DatabaseRowUrlAdapterException('fsockopen does not support SSL');
			}
			$host = 'ssl://'.$host;
			$port = 443;
		}
		elseif (!$port) {
			$port = 80;
		}

		if (!$path) {
			$path = '/';
		}

		$errno = null;
		$errstr = null;
		$fp = @fsockopen($host, $port, $errno, $errstr, 120);
		if (!$fp) {
			throw new DatabaseRowUrlException('PHP Socket Error: '.$errstr);
		}
		$out = "GET $path HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);

		$contents = '';
		while (!feof($fp)) {
			$contents .= fgets($fp, 1024);
		}
		fclose($fp);

		$response = false;
		list($headers, $response) = explode("\r\n\r\n", $contents, 2);

		if (!preg_match('#http/[0-9\.]+ 200 OK#i', $headers) || empty($response)) {
			$response = false;
		}

		return $response;
	}

	protected function _fetchFopen($url)
	{
		if (!ini_get('allow_url_fopen')) {
			throw new DatabaseRowUrlAdapterException('Adapter does not exist');
		}

		$response = @file_get_contents($url);

		return $response;
	}
}
