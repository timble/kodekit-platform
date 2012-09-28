<?
/**
 * @version     $Id: form.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('com://admin/default.view.grid.toolbar') ?>

<ktml:module position="sidebar">
    <?= @template('default_sidebar'); ?>
</ktml:module>

<form action="" method="get" class="-koowa-grid">
    <?= @template('default_scopebar') ?>
	<table>
		<thead>
			<tr>
				<th width="10">
				    <?= @helper('grid.checkall') ?>
				</th>
				<th>
					<?= @helper('grid.sort', array('column' => 'name')) ?>
				</th>
				<th>
					<?= @helper('grid.sort', array('column' => 'native_name', 'title' => 'Native Name')) ?>
				</th>
				<th width="10%">
					<?= @helper('grid.sort', array('column' => 'iso_code', 'title' => 'ISO Code')) ?>
				</th>
                <th width="50px" nowrap="nowrap">
                    <?= @text('Primary') ?>
                </th>
				<th width="15%" nowrap="nowrap">
					<?= @helper('grid.sort', array('column' => 'slug')) ?>
				</th>
				<th width="31px" nowrap="nowrap">
					<?= @helper('grid.sort', array('column' => 'enabled', 'title' => 'Enabled')) ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					 <?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<? foreach($languages as $language) : ?>
			<tr>
				<td align="center">
					<?= @helper('grid.checkbox', array('row' => $language)) ?>
				</td>
				<td>
					<a href="<?= @route('view=language&id='.$language->id) ?>"><?= $language->name ?></a>
				</td>
				<td>
					<?= $language->native_name ?>
				</td>
				<td align="center">
					<a  href="<?= @route('view=language&id='.$language->id) ?>"><?= $language->iso_code ?></a>
				</td>
                <td align="center">
                    <? if($language->primary): ?>
                        <img src="media://lib_koowa/images/star.png" alt="<?= @text( 'Primary Language' ) ?>" />
                    <? endif ?>
                </td>
				<td align="center">
					<?= $language->slug ?>
				</td>
				<td align="center">
					<? if($language->primary) : ?>
                	 	<?= @text('n/a') ?>
                    <? else: ?>
                    	<?= @helper('grid.enable', array('row' => $language)) ?>    
                    <? endif ?>
				</td>
			</tr>
			<? endforeach ?>
		</tbody>
	</table>
</form>