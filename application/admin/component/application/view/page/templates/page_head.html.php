<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
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

    <script src="assets://js/mootools.js" />
    <script src="assets://application/js/application.js" />
    <script src="assets://application/js/chromatable.js" />

    <style src="assets://application/stylesheets/default.css" />

    <script src="assets://application/js/jquery.js" /></script>
    <script type="text/javascript">
        var $jQuery = jQuery.noConflict();
    </script>
    <script src="assets://application/js/select2.js" /></script>
</head>
