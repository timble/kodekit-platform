<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.modal'); ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<div id="sidebar">
	<h3><?= @text('Categories') ?></h3>
	<?= @template('admin::com.categories.view.categories.list', array('categories' => KFactory::tmp('admin::com.weblinks.model.categories')->getList())); ?>
</div>

<form action="<?= @route() ?>" method="get" class="-koowa-grid">
    <?= @template('default_filter'); ?>
	<table class="adminlist">
	<thead>
		<tr>
			<th width="20">
				
			</th>
			<th class="title">
				<?= @helper('grid.sort', array('column' => 'title')) ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?= @helper('grid.sort', array('column' => 'published')) ?>
			</th>
			<th width="8%" nowrap="nowrap">
				<?= @helper('grid.sort', array('column' => 'ordering')) ?>
			</th>
		</tr>
		<tr>
			<td align="center">
				<?= @helper( 'grid.checkall'); ?>
			</td>
			<td>
				<?= @helper( 'grid.search'); ?>
			</td>
			<td></td>
			<td></td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="4">
				<?= @helper('paginator.pagination', array('total' => $total)) ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<? foreach ($weblinks as $weblink) : ?>
		<tr>
			<td align="center">
				<?= @helper('grid.checkbox', array('row' => $weblink))?>
			</td>
			<td>
				<a href="<?= @route( 'view=weblink&task=edit&id='. $weblink->id ); ?>"><?= @escape($weblink->title); ?></a>
			</td>
			<td align="center">
				<?= @helper('grid.enable', array('row' => $weblink)) ?>
			</td>
			<td class="order">
				<?= @helper('grid.order', array('row' => $weblink, 'total' => $total)); ?>
			</td>
		</tr>
		<? endforeach; ?>
	</tbody>
	</table>
</form>