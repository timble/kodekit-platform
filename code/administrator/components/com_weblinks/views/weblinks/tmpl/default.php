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
<?= @helper('behavior.tooltip'); ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route() ?>" method="get" class="-koowa-grid">
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
			<th width="15%" class="title">
				<?= @helper('grid.sort', array('column' => 'category')) ?>
			</th>
		</tr>
		<tr>
			<td align="center">
				<?= @helper( 'grid.checkall'); ?>
			</td>
			<td>
				<?= @template('admin::com.default.view.grid.search_form') ?>
			</td>
			<td align="center">
				<?= @helper('listbox.enabled', array('name' => 'published', 'attribs' => array('onchange' => 'this.form.submit();'))) ?>
			</td>
			<td></td>
			<td align="center">
				<?= @helper('listbox.category', array('attribs' => array('onchange' => 'this.form.submit();'))) ?>
			</td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="9">
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
				<span class="editlinktip hasTip" title="<?= @text( 'Edit Weblinks' );?>::<?= @escape($weblink->title); ?>">
					<a href="<?= @route( 'view=weblink&task=edit&id='. $weblink->id ); ?>"><?= @escape($weblink->title); ?></a>
				</span>
			</td>
			<td align="center">
				<?= @helper('grid.enable', array('row' => $weblink)) ?>
			</td>
			<td class="order">
				<?= @helper('grid.order', array('row' => $weblink, 'total' => $total)); ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?= @text( 'Edit Category' );?>::<?= @escape($weblink->category); ?>">
					<a href="<?= @route( 'option=com_categories&section=com_weblinks&task=edit&type=other&cid[]='. $weblink->catid ) ?>" >
				        <?= @escape($weblink->category); ?>
				  	</a>
				</span>
			</td>
		</tr>
		<? endforeach; ?>
	</tbody>
	</table>
</form>