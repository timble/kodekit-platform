<h3><?= @text('Tables') ?></h3>
<nav class="scrollable">
    <? foreach($tables as $table) : ?>
    <a class="<?= $state->table == $table->name ? 'active' : ''; ?>" href="<?= @route('table='.$table->name ) ?>">
    	<?= @escape($table->name) ?>
    </a>
    <? endforeach ?>
</nav>
