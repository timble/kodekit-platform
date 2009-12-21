<? defined('KOOWA') or die('Restricted access'); ?>

<ul>
<? foreach(@$boats as $boat) : ?>
    <li>
    	<?=$boat->id?>.
    	<a href="<?= @route('view=boat&id='.$boat->id); ?>">
    		<?=$boat->name?>
    	</a>
    </li>
<? endforeach; ?>
</ul>
