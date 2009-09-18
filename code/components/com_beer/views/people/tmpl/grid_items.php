<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<div id="beer_grid">
	<ul id="beer_grid" class="grid">
	<? foreach (@$people as $person) : ?>
		<li>
			<a href="<?=@route('view=person&id='.$person->slug) ?>"> <img
				src="http://www.gravatar.com/avatar.php?gravatar_id=<?= md5( strtolower($person->email) ); ?>&size=100"
				alt="<?= @$escape($person->name); ?>"
				title="<?= @$escape($person->name); ?>" /> 
				<strong><?= @$escape($person->name); ?></strong>
		    </a>
	    </li>
	<? endforeach; ?>
	</ul>
</div>
