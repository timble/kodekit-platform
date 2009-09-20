<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<div class="lettersmenu">
<ul>
	<li>
		<a href="<?= @route('&lletter=') ?>">
			<?= @text('Reset'); ?>
		</a>
	</li>
		
	<? foreach (@$letters_lastname as $alfa) : ?>
	<? if (@$state->lletter == $alfa->lletter) :
		$class = 'class="active" ';
	else :
		$class = '';
	endif; ?>
	<li>
		<a href="<?= @route('&lletter='.$alfa->lletter) ?>" <?= $class ?>>
		<?= $alfa->lletter; ?>
		</a>
	</li>
	<? endforeach; ?>
</ul>
</div>