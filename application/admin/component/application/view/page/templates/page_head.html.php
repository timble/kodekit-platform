<?
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<head>
    <base href="<?= @url(); ?>" />
    <title><?= @escape(@object('application')->getCfg('sitename' )). ' - ' .@text( 'Administration')  ?></title>
    <meta content="text/html; charset=utf-8" http-equiv="content-type"  />
    <meta content="chrome=1" http-equiv="X-UA-Compatible" />

    <ktml:meta />
    <ktml:link />
    <ktml:style />
    <ktml:script />

    <link href="media://application/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />

    <script src="media://js/mootools.js" />
    <script src="media://application/js/application.js" />
    <script src="media://application/js/chromatable.js" />

    <style src="media://application/stylesheets/default.css" />

    <script src="media://application/js/jquery.js" /></script>
    <script type="text/javascript">
        var $jQuery = jQuery.noConflict();
    </script>
    <script src="media://application/js/select2.js" /></script>
</head>
