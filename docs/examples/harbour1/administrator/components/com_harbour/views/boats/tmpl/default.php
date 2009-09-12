<? defined('KOOWA') or die; ?>
<ul>
<? foreach(@$boats as $boat) : ?>
    <li>
    	<?=$boat->id?>.
    	<?=$boat->name?>
    </li>
<? endforeach; ?>
</ul>
