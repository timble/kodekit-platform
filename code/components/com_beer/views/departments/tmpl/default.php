<? defined('_JEXEC') or die('Restricted access'); ?>

<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>

<div class="joomla ">
	<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="action" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= @$filter['order']; ?>" />
	<input type="hidden" name="filter_direction" value="<?= @$filter['direction']; ?>" />

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tfoot>
			<tr>
				<td align="center" colspan="6" class="sectiontablefooter">
					<?= @$pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<th width="5" align="center">
					<?= @text('NUM'); ?>
				</th>
				<th align="left">
					<?= @helper('grid.sort', 'Title', 'title', @$filter['direction'], @$filter['order']); ?>
				</th>
			</tr>
			<?php echo $this->loadTemplate('items'); ?>
		</tbody>
	</table>
	</form>
</div>