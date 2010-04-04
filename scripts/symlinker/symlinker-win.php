<?php
/**
 * @version     $Id$
 * @package     NookuSymlinker
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 *
 * Windows port by Ercan Özkaya
 */
include_once 'Console/CommandLine.php';
if(!class_exists('Console_CommandLine')) {
	die(PHP_EOL."You need PEAR Console_CommandLine to use this script. Install using the following command: ".PHP_EOL."pear install --alldeps Console_CommandLine\n\n");
}

// General
$parser = new Console_CommandLine();
$parser->description = "Make symlinks for Joomla extensions on Windows.";
$parser->version = '1.0';

// Arguments
$parser->addArgument('source', array(
    'description' => 'The source dir (usually from an IDE workspace)',
    'help_name'   => 'SOURCE'
));

$parser->addArgument('target', array(
    'description' => "the target dir (usually where a joomla installation resides",
    'help_name'   => 'TARGET'
));

// Parse input
try {
    $result = $parser->parse();
    $source = realpath($result->args['source']);
    $target = realpath($result->args['target']);
} catch (Exception $e) {
    $parser->displayError($e->getMessage());
    die;
}

// Make symlinks
if(file_exists($source)) 
{
	$it = new KSymlinker($source, $targte);
	while($it->valid()) {
		$it->next();
	}
}

class KSymlinker extends RecursiveIteratorIterator
{
	protected $_srcdir;
	protected $_tgtdir;
	
	public function __construct($srcdir, $tgtdir) 
	{
		$this->_srcdir = $srcdir;
		$this->_tgtdir = $tgtdir;
		
		parent::__construct(new RecursiveDirectoryIterator($this->_srcdir));
	}
	
	public function callHasChildren() 
	{							
		$filename = $this->getFilename();
		if($filename[0] == '.') {
			return false;
		}
				
		$src = $this->key();
				
		$tgt = str_replace($this->_srcdir, '', $src);		
		$tgt = str_replace('/site', '', $tgt);
  		$tgt = $this->_tgtdir.$tgt;
  		
  		if(is_link($tgt)) {
        	unlink($tgt);
        }
  		  		  		
  		if(!is_dir($tgt)) {
  			$this->createLink($src, $tgt); 		
  			return false;
  	  	}
  	  	
		return parent::callHasChildren();
	}
	
	public function createLink($src, $tgt) 
	{  		 
        if(!file_exists($tgt)) 
		{
			$opts = '';
			if ($src->isDir()) {
				$opts = '/D';
			}
			
			$cmd = "mklink $opts $tgt $full";
			exec($cmd);
			echo $full."\n\t--> $tgt\n";
		}
	}
}