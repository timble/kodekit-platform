<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<!--
<script src="media://js/koowa.js" />
-->

<link href="<?= @route('format=rss') ?>" rel="alternate" type="application/rss+xml" />

<div class="page-header">
    <h1><?= @escape($params->get('page_title')); ?></h1>
</div>

<? if ($category->image || $category->description) : ?>
<div class="clearfix">
    <? if ($category->image) : ?>
    <?= @helper('com:categories.string.image', array('row' => $category)) ?>
    <? endif; ?>
    <?= $category->description; ?>
</div>
<? endif; ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th width="100%">
                <?= @text('Name'); ?>
        	</th>
            <? if ($params->get('show_telephone', true)) : ?>
        	<th>
                <?= @text('Phone'); ?>
        	</th>
        	<? endif; ?>
        </tr>
    </thead>
    <tbody>
        <?= @template('default_items.html'); ?>
    </tbody>
</table>

<?= @helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)); ?>
