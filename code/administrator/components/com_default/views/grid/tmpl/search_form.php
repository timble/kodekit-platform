<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<input name="search" id="search" value="<?= $state->search;?>" />
<button onclick="this.form.submit();"><?= @text('Go')?></button>
<button onclick="document.getElementById('search').value='';this.form.submit();"><?= @text('Reset'); ?></button>