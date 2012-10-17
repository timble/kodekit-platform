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

	<? if(false) : ?>
	<script src="media://com_application/js/chromatable.js" />
	<style src="media://com_application/css/default.css" />
	<? else : ?>
    <script src="media://com_application/js/sidebar.js" />
    <script>
        window.addEvent('domready', function(){
            if(document.id('panel-sidebar') && document.id('panel-content')) {
                new Koowa.Sidebar({
                    sidebar: '#panel-sidebar',
                    observe: '#panel-content',
                    target: '.scrollable',
                    minHeight: 40,
                    scrollToActive: true
                });
            }
            if(document.id('panel-inspector') && document.id('panel-content')) {
                new Koowa.Sidebar({
                    sidebar: '#panel-inspector',
                    observe: '#panel-content',
                    target: '.scrollable',
                    minHeight: 40
                });
            }
            if(document.getElement('#panel-content .sidebar') && document.getElement('#panel-content .form-body')) {
                new Koowa.Sidebar({
                    sidebar: '#panel-content .sidebar',
                    observe: '#panel-content .form-body',
                    target: '.scrollable',
                    setObserveHeight: true
                });
            }
        });
    </script>
	<style src="media://com_application/css/legacy.css" />
	<? endif; ?>
	
    <style src="media://com_application/css/system.css"  />
</head>