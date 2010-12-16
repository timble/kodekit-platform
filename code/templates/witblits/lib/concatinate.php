<?php 
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

if (extension_loaded('zlib')) {
	// initialize ob_gzhandler function to send and compress data
	ob_start('ob_gzhandler');
}
 
// Function that removes comments, white space and line breaks from a CSS file
function CSSCompress($subject) {
	$subject = preg_replace('/\/\*([^*]|\*+[^\/*])*\*+\//', '', $subject); // Remove Comments
	$subject = preg_replace('/(?<=[,;:{\'\/\n\r])\s+/i', '', $subject); // Remove White Space
	$subject = preg_replace('/\s+(?=[^\w#!(-.])/i', '', $subject); // Remove odd left over cases of White Space (e.g. the gap inbetween err and the closeing { "b.err {...}")
	$subject = preg_replace('/\n/', '', $subject); // Remove any line breaks so the entire file is in one line
	return $subject;
}
 
// Do not compress unless it's a CSS file we're dealing with
// TODO: JavaScript minification (which I do have working using the YUI Compressor .jar file but this post was long enough already so left it out)
$compress = false;
 
// Check for a valid file type
if (strtolower($_GET['filetype']) == 'js') {
	$content_type = "javascript";
	$extention = ".js";
	$folder = "../js/";
} elseif (strtolower($_GET['filetype']) == 'css') {
	$content_type = "css";
	$extention = ".css";
	$folder = "../css/";
	$compress = true;
} else {
	die('An unknown file type was provided');
}
 
// send the requisite header information and character set
header ("content-type: text/$content_type; charset: UTF-8");
 
// initialize the 'compress' function to remove all comments and whitespace from the CSS files
if ($compress) {
	ob_start("CSSCompress");
}
 
// Grab each file and check they exist
$files = explode(",", $_GET['files']);
 
// List the files to be included
foreach ($files as $value) {
	if (file_exists($folder.$value.$extention)) {
		include $folder.$value.$extention;
	}
}
 
// set an thirty days in the future
// this works out as 60 seconds * by 60 minutes which equals 1 hr
// then times the 1hr by 24 to get 1 day
// then times 1 day by however many days you want the item to be cached for.
$offset = 60 * 60 * 24 * 30;
 
// set variable specifying format of expiration header
$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
 
// send cache expiration header to the client broswer
header ($expire);
 
// check cached credentials and reprocess accordingly
header ("cache-control: max-age=" . $offset . ", must-revalidate");
 
// to set last-modified we first get timestamp for past date we want...
$pf_time = strtotime("-3 days");
 
// ...then we format the date using the timestamp generated...
$pf_date = date("D, d M Y H:i:s", $pf_time);
 
// ...lastly we set the last-modified date header
header("Last-Modified: " . $pf_date . " GMT");
 
if (extension_loaded('zlib')) {
	ob_end_flush();
}
?>