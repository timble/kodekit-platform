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
 * @example
 * // in child view class
 * public function display()
 * {
 * 		$this->assign('path', 'path/to/file');
 * 		// OR
 * 		$this->assign('body', $file_contents);
 * 
 * 		$this->assign('filename', 'foobar.pdf'); 
 *
 * 		// optional:
 * 		$this->assign('mimetype', 'application/pdf');
 * 		$this->assign('disposition', 'inline'); // defaults to 'attachment' to force download
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
	/**
	 * Renders and echo's the views output
 	 *
	 * @return KViewFile
	 */
	public function display()
	{
		// For a certain unmentionable browser
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}
		
		// Remove php's time limit
	    if(!ini_get('safe_mode') ) {
		    @set_time_limit(0);
        }

		// Mimetype
		// @todo magic mimetypes?
		if($this->mimetype) {
			header('Content-type: '.$this->mimetype);
		} 
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');

		// Prevent caching
		header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");
        
		// Clear buffer
        while (@ob_end_clean());
    
		$this->filename = basename($this->filename);		
    	if(isset($this->body)) // File body is passed as string
    	{
			if(empty($this->filename)) {
				throw new KViewException('No filename supplied');
			}
			$this->_disposition();
			$filesize = strlen($this->body);
			header('Content-Length: '.$filesize);
			flush();
			echo $this->body;
    	}
    	elseif(isset($this->path)) // File is read from disk
    	{
     		if(empty($this->filename)) {
				$this->filename = basename($this->path);				
			}
			$filesize = @filesize($this->path);
			header('Content-Length: '.$filesize);
    		$this->_disposition();
			flush();
			$this->_readChunked($this->path);
    	}
    	else throw new KViewException('No body or path supplied');
		
		die;
	}

	protected function _disposition()
	{
		// @todo:
		// Content-Disposition: inline; filename="foo"; modification-date="'.$date.'"; size=123;
			
		if(isset($this->disposition) && $this->disposition == 'inline') 
		{		
			header('Content-Disposition: inline; filename="'.$this->filename.'"');
		} else {	
			header('Content-Description: File Transfer');
			header('Content-type: application/force-download');
			header('Content-Disposition: attachment; filename="'.$this->filename.'"');
		}
		return $this;
	}
	
    protected function _readChunked($path)
    {
   		$chunksize	= 1*(1024*1024); // Chunk size
   		$buffer 	= '';
   		$cnt 		= 0;
   		
   		$handle = fopen($path, 'rb');
   		if ($handle === false) {
       		throw new KViewException('Cannot open file');
   		}
   		
   		while (!feof($handle)) {
       		$buffer = fread($handle, $chunksize);
       		echo $buffer;
			@ob_flush();
			flush();
       		$cnt += strlen($buffer);
   		}
       $status = fclose($handle);
   	   return $cnt; 
	}
}
