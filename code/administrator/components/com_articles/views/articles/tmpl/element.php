<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route('tmpl=component') ?>" method="get" class="-koowa-grid">
	<table class="adminlist" cellspacing="1">
		<thead>
			<tr>
				<th class="title">
				    <?= @helper('grid.sort', array('title' => 'Title', 'column' => 'title')) ?>
				</th>
				<th width="7%">
					<?= @helper('grid.sort', array('title' => 'Access', 'column' => 'access')) ?>
				</th>
				<th class="title" width="15%" nowrap="nowrap">
					<?= @helper('grid.sort', array('title' => 'Section', 'column' => 'section_title')) ?>
				</th>
				<th class="title" width="15%" nowrap="nowrap">
					<?= @helper('grid.sort', array('title' => 'Category', 'column' => 'category_title')) ?>
				</th>
				<th align="center" width="10">
					<?= @helper('grid.sort', array('title' => 'Date', 'column' => 'created_on')) ?>
				</th>
			</tr>
			<tr>
				<td>
				    <?= @helper('grid.search') ?>
				</td>
				<td></td>
				<td>
					<?= @helper('listbox.sections', array('selected' => $state->section, 'attribs' => array('id' => 'articles-form-section'))) ?>
				</td>
				<td>
					<?= @helper('listbox.categories', array('selected' => $state->category, 'attribs' => array('id' => 'articles-form-category'))) ?>
				</td>
				<td></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<? foreach($articles as $article) : ?>
			<tr>
				<td>
					<a style="cursor: pointer;" onclick="window.parent.jSelectArticle('<?= $article->id ?>', '<?= str_replace(array("'", "\""), array("\\'", ""), $article->title); ?>', '<?php echo JRequest::getVar('object'); ?>');">
					    <?= @escape($article->title) ?>
					</a>
				</td>
				<td align="center">
				    <?= $article->group_name ?></td>
				<td>
					<?= $article->section_id ? $article->section_title : @text('Uncategorised') ?>
				</td>
				<td>
					<?= $article->category_id ? $article->category_title : @text('Uncategorised') ?>
				</td>
				<td nowrap="nowrap">
					<?= @helper('date.humanize', array('date' => $article->created_on)) ?>
				</td>
			</tr>
		<? endforeach ?>
		</tbody>
	</table>
</form>