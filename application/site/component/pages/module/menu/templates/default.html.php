<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<? $title = $module->getParameters()->get('show_title', false) ? $module->title : null; ?>

<nav role="navigation">
    <?= helper('com:pages.list.pages', array(
        'pages'   => $pages,
        'active'  => $active,
        'title'   => $title,
        'attribs' => array('class' => $module->getParameters()->get('class', 'nav')))) ?>
</nav>