<?php
/**
 * @version     $Id: form.php 1638 2011-06-07 23:00:45Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<form action="<?= @route() ?>" method="get">cewew
<? if($parameters->get('show_page_title')) : ?>
	<div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
	    <?= @escape($parameters->get('page_title')) ?>
	</div>
<? endif ?>
	<p>
		<? if($parameters->get('filter')) : ?>
		    <?= @text('Filter').'&nbsp;'; ?>
			<input type="text" name="search" value="<?= @escape($state->search) ?>" class="inputbox" />
		<? endif ?>
		<?= @helper('listbox.months') ?>
		<?= @helper('listbox.years') ?>

		<?php //echo $this->form->limitField; ?>
		<button type="submit" class="button"><?= @text('Filter') ?></button>
	</p>

    <?= @template('archived_items', array('articles' => $articles, 'parameters' => $parameters)) ?>
</form>