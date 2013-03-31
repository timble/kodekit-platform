<form action="<?= @route('row='.$state->row.'&table='.$state->table) ?>" method="post">
    <input type="hidden" name="row" value="<?= $state->row ?>" />
    <input type="hidden" name="table" value="<?= $state->table ?>" />

    <textarea type="text" name="text" placeholder="<?= @text('Add new comment here ...') ?>" id="new-comment-text" class="input-block-level"></textarea>
    <input class="btn btn-primary" type="submit" value="<?= @text('Comment') ?>"/>
</form>