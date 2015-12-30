<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
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