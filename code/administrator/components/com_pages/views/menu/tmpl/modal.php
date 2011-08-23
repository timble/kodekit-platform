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

<form action="<?= @route('&id='.$menu->id)?>" method="post" name="adminForm">
	<div class="-koowa-container-12">
		<div class="-koowa-grid-12">
			<input type="text" name="title" placeholder="<?= @text('Title') ?>" value="<?= $menu->title; ?>" />
		</div>

		<table class="adminform">
			<tr>
				<td width="100">
					<label for="name">
						<strong><?= @text('Unique Name') ?>:</strong>
					</label>
				</td>
				<td>
					<input class="inputbox" type="text" name="name" size="30" maxlength="25" value="<?= $menu->name ?>" />
				</td>
			</tr>
			<tr>
				<td width="100" >
					<label for="description">
						<strong><?= @text('Description') ?>:</strong>
					</label>
				</td>
				<td>
					<textarea name="description" rows="3" placeholder="<?= @text('Description') ?>" maxlength="255"><?= $menu->description ?></textarea>
				</td>
			</tr>
		</table>
	</div>
</form>