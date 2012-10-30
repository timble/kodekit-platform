<?
/**
 * @version     $Id: default.php 4558 2012-08-11 21:12:47Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<head>
    <base href="<?= KRequest::url(); ?>" />
    <title><?= @escape(@service('application')->getCfg('sitename' )). ' - ' .@text( 'Administration')  ?></title>
    <meta content="text/html; charset=utf-8" http-equiv="content-type"  />
    <meta content="chrome=1" http-equiv="X-UA-Compatible" />

    <ktml:meta />
    <ktml:link />
    <ktml:style />
    <ktml:script />

    <link href="media://com_application/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	
	<script src="media://lib_koowa/js/mootools.js" />
	<script src="media://com_application/js/application.js" />
    <script src="media://com_application/js/chromatable.js" />

	<? if(true) : ?>
	<style src="media://com_application/css/default.css" />
	<? else : ?>
    <script src="media://com_application/js/sidebar.js" />
	<style src="media://com_application/css/legacy.css" />
	<? endif; ?>
	
    <style src="media://com_application/css/system.css"  />
</head>