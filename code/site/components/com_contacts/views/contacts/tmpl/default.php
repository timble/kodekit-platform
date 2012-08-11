<?
/**
 * @version		$Id: default.php 3537 2012-04-02 17:56:59Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
-->

<? if ($params->get('show_feed_link', 1) == 1) : ?>
	<link href="<?= @route('format=rss') ?>" rel="alternate" type="application/rss+xml" />
<? endif; ?>

<h1><?= @escape($params->get('page_title')); ?></h1>

<? if ( $category->image || $category->description ) : ?>
<? if (isset($category->image)) : ?>
    <img src="<?= $category->image->path ?>" height="<?= $category->image->height ?>" width="<?= $category->image->width ?>" />
    <? endif; ?>
    <?= $category->description; ?>
<? endif; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
    <? if ($params->get( 'show_headings' )) : ?>
    <thead>
        <tr>
            <th height="20">
                <?= @text( 'Name' ); ?>
        	</th>
            <? if ( $params->get( 'show_position' ) ) : ?>
            <th height="20">
                <?= @text( 'Position' ); ?>
            </th>
            <? endif; ?>
            <? if ( $params->get( 'show_email' ) ) : ?>
            <th height="20" width="20%">
                <?= @text( 'Email' ); ?>
        	</th>
            <? endif; ?>
            <? if ( $params->get( 'show_telephone' ) ) : ?>
        	<th height="20" width="15%">
                <?= @text( 'Phone' ); ?>
        	</th>
        	<? endif; ?>
        </tr>
    </thead>
    <? endif; ?>
    <tbody>
        <?= @template('default_items'); ?>
    </tbody>
</table>
