<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>

<form action="" method="get" class="-koowa-grid">
	<table>
		<thead>
			<tr>
				<th class="title">
				    <?= helper('grid.sort', array('title' => 'Title', 'column' => 'title')) ?>
				</th>
				<th align="center" width="10">
					<?= helper('grid.sort', array('title' => 'Date', 'column' => 'created_on')) ?>
				</th>
			</tr>
			<tr>
				<td>
				    <?= helper('grid.search') ?>
				</td>
				<td></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">
					<?= helper('com:theme.paginator.pagination') ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<? foreach($articles as $article) : ?>
			<tr>
				<td>
					<a style="cursor: pointer;" onclick="window.parent.jSelectArticle('<?= $article->id ?>', '<?= str_replace(array("'", "\""), array("\\'", ""), $article->title); ?>', '<?= object('request')->query->get('object', 'cmd'); ?>');">
					    <?= escape($article->title) ?>
					</a>
				</td>
				<td nowrap="nowrap">
					<?= helper('date.humanize', array('date' => $article->created_on)) ?>
				</td>
			</tr>
		<? endforeach ?>
		</tbody>
	</table>
</form>