<?php
/**
 * @version     $Id:  $
 * @package     Koowa_Http
 * @subpackage  Client
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * HTTP Client Adapter interface
 * 
 * @author      Laurens Vandeput <laurens@joomlatools.org>
 * @package     Koowa_Http
 * @subpackage  Client
 * @version     1.0
 */
interface KHttpClientAdapter 
{
	public function connect(KHttpUri $uri);
	
	public function disconnect();
	
	public function read();
	
	public function write(KHttpUri $uri, $options);
}