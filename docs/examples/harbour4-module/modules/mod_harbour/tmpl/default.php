<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<ul>
<? foreach(@$boats as $boat) : ?>
	<li><?=$boat->name?></li>
<? endforeach; ?>
</ul>