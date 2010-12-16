<?php
header("content-type: text/css; charset: UTF-8");

$csspath = JPATH . 'templates/witblits/css/'
	include($csspath'reset.css');
	if($grid_columns == 16) :
	include($csspath'960.css');
	else :
	include($csspath'960_24col.css');
	endif;
	include($csspath'template.css');
	include($csspath'typo.css');
	include($csspath'joomla.css');
	include($csspath'modules.css');
	include($csspath'menu.css');
	include($csspath'system.css');
	if($enable_tooltips == 1) :
	include($csspath'tooltips.css');
	endif;
	if( $direction == 'rtl'){ include('rtl.css'); }
	// browser specific
	if($browser == 'firefox') include('browsers/firefox.css');
	if($browser == 'safari') include('browsers/safari.css');
	if($browser == 'opera') include('browsers/opera.css');
	if($browser == 'chrome') include('browsers/chrome.css');
	if($browser == 'ie') include('browsers/ie.css');
	if($browser == 'ie6') include('browsers/ie6.css');
	if($browser == 'ie7') include('browsers/ie7.css');
	if($browser == 'ie8') include('browsers/ie8.css');
	if($browser == 'ie9') include('browsers/ie9.css');
	if(preg_match('/MSIE 8/i', $_SERVER['HTTP_USER_AGENT'])) include('browsers/ie8.css');
	if(preg_match('/MSIE 7/i', $_SERVER['HTTP_USER_AGENT'])) include('browsers/ie7.css');
	if(preg_match('/MSIE 6/i', $_SERVER['HTTP_USER_AGENT'])) include('browsers/ie6.css');

?>