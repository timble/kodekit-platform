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
        'max_level'   => parameters()->get('max_level', 9),
        'active_only' => parameters()->get('active_only', false),
        'title'       => parameters()->show_title ? title() : null,
        'attribs'     => array('class' => parameters()->get('class', 'nav')))) ?>
</nav>