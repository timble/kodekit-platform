<? /** $Id: form_filter_firstname.php 215 2009-09-20 03:27:20Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<div class="lettersmenu">
<ul>
	<li>
		<a href="<?= @route('&fletter=') ?>">
			<?= @text('Reset'); ?>
		</a>
	</li>
		
	<? foreach (@$letters_firstname as $alfa) : ?>
	<? if (@$state->fletter == $alfa->fletter) :
		$class = 'class="active" ';
	else :
		$class = '';
	endif; ?>
	<li>
		<a href="<?= @route('&fletter='.$alfa->fletter) ?>" <?= $class ?>>
		<?= $alfa->fletter; ?>
		</a>
	</li>
	<? endforeach; ?>
</ul>
</div>