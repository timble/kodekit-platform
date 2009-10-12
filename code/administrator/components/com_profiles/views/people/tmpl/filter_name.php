<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<ul class="filter-letters">	
	<span><?= @text('Name'); ?>: </span>
	<? foreach(range('A','Z') as $letter) : ?>
	<? $class = (@$state->letter_name == $letter) ? 'class="active" ' : ''; ?>
	<li>
		<? if(in_array($letter, @$letters_name)) : ?>
		<a href="<?= @route('letter_name='.$letter) ?>" <?= $class ?>>
			<?= $letter; ?>
		</a>
		<? else : ?>
		<span>
			<?= $letter; ?>
		</span>
		<? endif; ?>
	</li>
	<? endforeach; ?>
	<li>
		<a href="<?= @route('letter_name=') ?>">
			<?= @text('Reset'); ?>
		</a>
	</li>
</ul>