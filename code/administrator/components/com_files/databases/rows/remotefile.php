<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Remote File Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesDatabaseRowRemotefile extends KDatabaseRowAbstract
{
	/**
	 * Adapters to use for remote access
	 * @var array
	 */
	protected $_adapters = array();

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		if (isset($config->adapters)) {
			$this->_adapters = $config->adapters;
		}
	}

	protected function _initialize(KConfig $config)
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
			throw new ComFilesDatabaseRowRemotefileException('File cannot be downloaded');
		}

		$this->contents = $response;

		return true;
	}

	protected function _fetch($url)
	{
		$response = false;
		foreach ($this->_adapters as $i => $adapter) {
			try {
				$function = '_fetch'.ucfirst($adapter);
				$response = $this->$function($url);
				break;
			}
			catch (ComFilesDatabaseRowRemotefileAdapterException $e) {
				continue;
			}
			catch (ComFilesDatabaseRowRemotefileException $e) {

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
			throw new ComFilesDatabaseRowRemotefileAdapterException('Adapter does not exist');
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS,		 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 		 120);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			throw new ComFilesDatabaseRowRemotefileException('Curl Error: '.curl_error($ch));
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
			throw new ComFilesDatabaseRowRemotefileAdapterException('Adapter does not exist');
		}

		$uri = KFactory::tmp('lib.koowa.http.url', array('url' => $url));

		$scheme = $uri->get(KHttpUrl::PART_SCHEME);
		$host = $uri->get(KHttpUrl::PART_HOST);
		$port = $uri->get(KHttpUrl::PART_PORT);
		$path = $uri->get(KHttpUrl::PART_PATH | KHttpUrl::PART_FORMAT | KHttpUrl::PART_QUERY | KHttpUrl::PART_FRAGMENT);

		if ($scheme == 'https://') {
			if (!in_array('ssl', stream_get_transports())) {
				throw new ComFilesDatabaseRowRemotefileAdapterException('fsockopen does not support SSL');
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
			throw new ComFilesDatabaseRowRemotefileException('PHP Socket Error: '.$errstr);
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
			throw new ComFilesDatabaseRowRemotefileAdapterException('Adapter does not exist');
		}

		$response = file_get_contents($url);

		return $response;
	}
}

class ComFilesDatabaseRowRemotefileException extends KException {}
class ComFilesDatabaseRowRemotefileAdapterException extends KException {}
