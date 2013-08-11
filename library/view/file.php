<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * File View
 *
 * Use to force browser to download a file from the file system
 *
 * <code>
 * // in child view class
 * public function render()
 * {
 *      $this->path = path/to/file');
 *      // OR
 *      $this->setContent($file_contents);
 *
 *      $this->filename = foobar.pdf';
 *
 *      // optional:
 *      $this->mimetype    = 'application/pdf';
 *      $this->disposition =  'inline'; // defaults to 'attachment' to force download
 *
 *      return parent::render();
 * }
 * </code>
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\View
 */
class ViewFile extends ViewAbstract
{
    /**
     * The file path
     *
     * @var string
     */
    public $path = '';

    /**
     * The file name
     *
     * @var string
     */
    public $filename = '';

    /**
     * The file disposition
     *
     * @var string
     */
    public $disposition = 'attachment';
    
    /**
     * Transport method
     * @var string
     */
	public $transport;

    /**
     * Constructor
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        foreach ($config->toArray() as $key => $value) {
        	$this->$key = $value;
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $count = count($this->getIdentifier()->path);

        $config->append(array(
            'path'        => '',
            'filename'    => $this->getIdentifier()->path[$count-1].'.'.$this->getIdentifier()->name,
            'disposition' => 'attachment',
            'transport'   => 'php'
        ));

        parent::_initialize($config);
    }

    /**
     * Return the views output
     *
     * @return void
     */
    public function render()
    {
        if (empty($this->path) && empty($this->_content)) {
        	throw new \UnexpectedValueException('No content or path supplied');
        }

    	// For a certain unmentionable browser
    	if(ini_get('zlib.output_compression')) {
    		@ini_set('zlib.output_compression', 'Off');
    	}
    	
    	// fix for IE7/8
    	if(function_exists('apache_setenv')) {
    	    apache_setenv('no-gzip', '1');
    	}

    	// Remove php's time limit
    	if(!ini_get('safe_mode')) {
    		@set_time_limit(0);
    	}

    	// Clear buffer
    	while (@ob_end_clean());

    	$this->filename = basename($this->filename);

    	if (!empty($this->_content))
    	{ 
    		// File body is passed as string
    		if (empty($this->filename)) {
    			throw new \UnexpectedValueException('No filename supplied');
    		}
    	} 
    	elseif (!empty($this->path)) 
    	{ 
    		// File is read from disk
    		if (empty($this->filename)) {
    			$this->filename = basename($this->path);
    		}
    	}

		//Force transport to php if output is a string
		if (!empty($this->_content)) {
			$transport = '_transportPhp';
        } else {
        	$transport = '_transport'.ucfirst(strtolower($this->transport));
        }
        
    	if (!method_exists($this, $transport)) {
    	    throw new \RuntimeException('Transport method :'.$this->transport.'not found');
    	}
  	
    	return $this->$transport();
    	die;
    }
    
    protected function _transportPhp()
    {
        $this->filesize = $this->path ? filesize($this->path) : strlen($this->_content);
    
        if (!$this->filesize) {
            throw new \RuntimeException('Cannot read file');
        }

        $this->start_point = 0;
        $this->end_point = $this->filesize - 1;
    
        $this->_setHeaders();

        if ($this->getObject('request')->headers->has('Range'))
        {
            // Partial download
            $range = $this->getObject('request')->headers->get('Range');
            $parts = explode('-', substr($range, strlen('bytes=')));

            $this->start_point = $parts[0];
            if (isset($parts[0])) {
                $this->start_point = $parts[0];
            }
    
            if (isset($parts[1]) && $parts[1] <= $this->filesize-1) {
                $this->end_point = $parts[1];
            }
    
            if ($this->start_point > $this->filesize) {
                throw new \OutOfRangeException('Invalid start point given in range header');
            }
    
            header('HTTP/1.0 206 Partial Content');
            header('Status: 206 Partial Content');
            header('Accept-Ranges: bytes');
            header('Content-Range: bytes '.$this->start_point.'-'.$this->end_point.'/'.$this->filesize);
            header('Content-Length: '.($this->end_point - $this->start_point + 1), true);
        }

        flush();
    
        if ($this->_content)
        {
            $this->file = tmpfile();
            fwrite($this->file, $this->_content);
            fseek($this->file, 0);
        }
        else $this->file = fopen($this->path, 'rb');
    
        if ($this->file === false) {
            throw new \RuntimeException('Cannot open file');
        }
    
        $buffer     = '';
        $cnt        = 0;
    
        if ($this->start_point > 0) {
            fseek($this->file, $this->start_point);
        }
    
        //serve data chunk and update download progress log
        $count = $this->start_point;
        while (!feof($this->file) && $count <= $this->end_point)
        {
            //calculate next chunk size
            $chunk_size = 1*(1024*1024);
            if ($count + $chunk_size > $this->end_point + 1) {
                $chunk_size = $this->end_point - $count + 1;
            }
    
            //get data chunk
            $buffer = fread($this->file, $chunk_size);
            if (!$buffer) {
                throw new \RuntimeException('Could not read file');
            }
    
            echo $buffer;
            @ob_flush();
            flush();
            $cnt += strlen($buffer);
        }

        if (!empty($this->file) && is_resource($this->file)) {
            fclose($this->file);
        }
    
        return $cnt;
    }
    
    protected function _transportApache()
    {
        if (empty($this->path)) {
            throw new \UnexpectedValueException('File path is missing');
        }
    
        $this->_setHeaders();
        header('X-Sendfile: '.$this->path);
    }
    
    protected function _transportNginx()
    {
        if (empty($this->path)) {
            throw new \UnexpectedValueException('File path is missing');
        }
    
        $this->_setHeaders();
        $path = preg_replace('/'.preg_quote(JPATH_ROOT, '/').'/', '', $this->path, 1);
        header('X-Accel-Redirect: '.$path);
    }
    
    protected function _transportLighttpd()
    {
        if (empty($this->path)) {
            throw new \UnexpectedValueException('File path is missing');
        }
    
        $this->_setHeaders();
        header('X-LIGHTTPD-send-file: '.$this->path); // For v1.4
        header('X-Sendfile: '.$this->path); // For v1.5
    }
    
    protected function _setHeaders()
    {
 		//Mimetype
        if ($this->mimetype) {
            header('Content-type: '.$this->mimetype);
        }
    
        //Disposition
        if(isset($this->disposition) && $this->disposition == 'inline') {
            header('Content-Disposition: inline; filename="'.$this->filename.'"');
        } else {
            header('Content-Description: File Transfer');
            header('Content-type: application/force-download');
            header('Content-Disposition: attachment; filename="'.$this->filename.'"');
        }

        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
    
        //Caching
        header("Pragma: no-store,no-cache");
        header("Cache-Control: no-cache, no-store, must-revalidate, max-age=-1");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Expires: Mon, 14 Jul 1789 12:30:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    
        //Lenght
        if (!empty($this->filesize)) {
            header('Content-Length: '.$this->filesize);
        }
    }    
}
