<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<div class="header pagetitle icon-48-<?= $toolbar->getIcon() ?>">
<? if (version_compare(JVERSION,'1.6.0','ge')) : ?>
	<h2><?= @text($toolbar->getTitle()) ?></h2>
<? else : ?>
    <?= @text($toolbar->getTitle()) ?>
<? endif; ?>
</div>