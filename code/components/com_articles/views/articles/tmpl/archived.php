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