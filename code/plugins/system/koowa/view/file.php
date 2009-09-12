<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Use to force browser to download a file from the file system
 *
 * Example:
 * public function display()
 * {
 * 		$this->assign('filename', 'foobar.pdf');
 * 		$this->assign('path', 'path/to/file');
 *
 * 		// optional:
 * 		$this->assign('mimetype', 'application/pdf');
 *
 * 		return parent::display();
 * }
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_View
 */
class KViewFile extends KViewAbstract
{
	public function display()
	{
		// For a certain unmentionable browser
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		// Filename and mimetype
		if($this->mimetype) {
			header('Content-type: '.$this->mimetype);
		} else {
			header('Content-type: application/force-download');
		}
		if($this->filename) {
			header('Content-Disposition: attachment; filename="'.$this->filename.'"');
		}
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');

		// prevent caching
		header("Cache-control: private");
		header('Pragma: private');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

		// Filesize
		$filesize = filesize($this->path);
		header('Content-Length: '.$filesize);

		// @TODO split in chunks,  support multipart

		// Output
		if($this->path) {
			readfile($this->path);
		}
	}
}