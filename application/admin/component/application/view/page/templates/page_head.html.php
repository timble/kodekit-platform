<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<head>
    <base href="<?= url(); ?>" />
    <title><?= title().' - '. translate( 'Administration'); ?></title>

    <meta content="text/html; charset=utf-8" http-equiv="content-type"  />
    <meta content="chrome=1" http-equiv="X-UA-Compatible" />

    <ktml:title>
    <ktml:meta>
    <ktml:link>
    <ktml:style>
    <ktml:script>

    <link href="assets://application/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />

    <ktml:script src="assets://js/mootools.js" />
    <ktml:script src="assets://application/js/application.js" />
    <ktml:script src="assets://application/js/chromatable.js" />

    <ktml:style src="assets://application/stylesheets/default.css" />

    <ktml:script src="assets://application/js/jquery.js" />
    <script>
        var $jQuery = jQuery.noConflict();
    </script>
    <ktml:script src="assets://application/js/select2.js" />
</head>
