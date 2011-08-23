<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<style src="media://com_pages/css/menus-default.css" />

<form id="menus-form" action="<?= @route()?>" method="get" class="-koowa-grid">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($menus); ?>);" />
				</th>
				<th width="100%">
					<?= @helper('grid.sort', array('column' => 'title' , 'title' => 'Title')); ?>
				</th>
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
		<? foreach ($menus as $menu) : ?>
			<tr>
				<td align="center">
		            <?= @helper('grid.checkbox',array('row' => $menu)); ?>
				</td>
				<td>
		            <? if(!$state->trash) : ?>
					<a href="<?= @route('view=menu&id='.$menu->id); ?>">
						<strong><?= @escape($menu->title); ?></strong> (<?= $menu->name; ?>)
					</a>
		            <? else : ?>
						<strong><?= @escape($menu->title); ?></strong> (<?= $menu->name; ?>)
		            <? endif; ?>
				</td>
			</tr>
        <? endforeach; ?>
		</tbody>
	</table>
</form>