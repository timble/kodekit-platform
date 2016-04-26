<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<nav role="navigation">
    <?= helper('com:pages.menu.render', array(
        'pages'       => $pages,
        'max_level'   => parameter('max_level', 9),
        'active_only' => parameter('active_only', false),
        'title'       => parameter('show_title') ? title() : null,
        'attribs'     => array('class' => parameter('class', 'nav')))) ?>
</nav>