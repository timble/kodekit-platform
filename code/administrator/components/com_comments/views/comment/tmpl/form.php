<div id="comments-comment-form">
	<form action="<?= @route('row='.@$state->row.'&table='.$state->table) ?>" method="post">
        <input type="hidden" name="row" value="<?= $state->row ?>" />
        <input type="hidden" name="table" value="<?= $state->table ?>" />

        <textarea type="text" name="text" placeholder="<?= @text('Add new comment here ...') ?>" id="new-comment-text"></textarea>
        <br />
        <input class="button" type="submit" value="<?= @text('Submit') ?>"/>
    </form>
</div>