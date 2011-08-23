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

<style src="media://com_pages/css/page-modal.css" />

<form action="<?= @route('&id='.$page->id) ?>" method="post" name="adminForm">
	<div class="-koowa-container-16">
		<div class="-koowa-grid-16" style="margin-bottom:15px">
			<input type="text" name="title" placeholder="<?= @text('Title') ?>" value="<?= $page->title ?>" size="50" maxlength="255" />
		</div>
		<div class="clear"></div>

		<div class="-koowa-grid-8">
			<?//= @template('form_general') ?>
		</div>
		<div class="-koowa-grid-8">
			<?//= @template('form_details'); ?>
		</div>
	</div>
</form>