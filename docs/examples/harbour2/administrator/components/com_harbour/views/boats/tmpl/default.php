<ul>
<? foreach(@$boats as $boat) : ?>
    <li>
    	<?=$boat->id?>.
    	<a href="<?=$boat->link?>">
    		<?=$boat->name?>
    	</a>
    </li>
<? endforeach; ?>
</ul>
