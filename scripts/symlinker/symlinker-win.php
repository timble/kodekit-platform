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

// Defines
define('DS', DIRECTORY_SEPARATOR);
define('SRCDIR', $source);
$srcdirs = array(
	SRCDIR.'/administrator/components',
	SRCDIR.'/administrator/language/en-GB',
	SRCDIR.'/administrator/modules',
	SRCDIR.'/components',
	SRCDIR.'/media',
	SRCDIR.'/language/en-GB',
	SRCDIR.'/modules',
	SRCDIR.'/plugins/authentication',
	SRCDIR.'/plugins/content',
	SRCDIR.'/plugins/editors',
	SRCDIR.'/plugins/editors-xtd',
	SRCDIR.'/plugins/koowa',
	SRCDIR.'/plugins/search',
	SRCDIR.'/plugins/system',
	SRCDIR.'/plugins/user',
	SRCDIR.'/plugins/xmlrpc',
);


// Make symlinks
$restricted = array('.git', '.svn', '.cvs', '.settings', '.buildpath', '.gitignore', '.project');
foreach ($srcdirs as $srcdir) {
	if (file_exists($srcdir)) {
		$it = new DirectoryIterator($srcdir);
		foreach ($it as $src) {
			$filename = $src->getFilename();
			if ($src->isDot() || in_array($filename, $restricted)) {
				continue;
			}
			$full = realpath($src->getPathName());
			$tgt = str_replace('/', DS, $target.str_replace(SRCDIR, '', $srcdir).'/'.$filename);
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