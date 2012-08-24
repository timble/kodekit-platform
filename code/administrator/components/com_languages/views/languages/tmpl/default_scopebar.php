<div class="scopebar">
    <div class="scopebar-group">
        <a href="<?= @route('enabled=') ?>" class="<?= is_null($state->enabled) ? 'active' : '' ?>">
            <?= @text('All') ?>
        </a>
    </div>
    <div class="scopebar-group">
        <a href="<?= @route('enabled=1') ?>" class="<?= $state->enabled === true ? 'active' : '' ?>">
            <?= @text('Enabled') ?>
        </a>
        <a href="<?= @route('enabled=0') ?>" class="<?= $state->enabled === false ? 'active' : '' ?>">
            <?= @text('Unpublished') ?>
        </a>
    </div>
    <div class="scopebar-search">
        <?= @helper('grid.search') ?>
    </div>
</div>