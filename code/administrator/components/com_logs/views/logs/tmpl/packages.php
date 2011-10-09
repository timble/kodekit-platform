<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<h3><?=@text( 'Components' )?></h3>

<?php if (count($packages)): ?>
<ul>
	<?php foreach ($packages as $package): ?>
		<?php if ($package->id == $state->package): ?>
			<li class="active">
		<?php else: ?> <li> <?php endif ?>
			<a href="<?=@route('view=logs&package='.$package->id)?>"><?=ucfirst($package->package)?></a>
		</li>	
	<?php endforeach ?>
</ul>	
<?php endif ?>
