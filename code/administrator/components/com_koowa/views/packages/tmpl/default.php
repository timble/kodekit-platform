<? /* $Id$ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>
<h2 class="working">
    <?= $total === 1 ? @text('Found 1 install package') : sprintf(@text('Found %d packages'), $total) ?>
</h2>