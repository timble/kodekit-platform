<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<? $title = $show_title ? $module->title : null; ?>

<nav role="navigation">
    <?= helper('com:pages.list.pages', array('pages' => $pages, 'active' => $active, 'title' => $title, 'attribs' => array('class' => $class))) ?>
</nav>