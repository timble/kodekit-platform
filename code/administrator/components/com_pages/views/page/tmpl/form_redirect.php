<div class="control-group" id="page-link-type">
    <label class="control-label" for="parent"><?= @text('Type') ?></label>
    <div id="parent" class="controls">
    	<label class="radio">
    	    <input type="radio" name="link_type" value="id" <?= $page->isNew() || $page->link_id ? 'checked="checked"' : '' ?> />
    	    <?= @text('Page') ?>
    	</label>
    	
    	<label class="radio">
    	    <input type="radio" name="link_type" value="url" <?= $page->link_url ? 'checked="checked"' : '' ?> />
    	    <?= @text('URL') ?>
    	</label>
    </div>
</div>
<div class="control-group" id="page-link-id">
    <label class="control-label" for="parent"><?= @text('Page') ?></label>
    <div id="parent" class="controls">
    	<?= @helper('listbox.pages', array('name' => 'link_id', 'selected' => $page->link_id)) ?>
    </div>
</div>
<div class="control-group" id="page-link-url">
    <label class="control-label" for="parent"><?= @text('URL') ?></label>
    <div id="parent" class="controls">
    	<input type="text" name="link_url" value="<?= $page->link_url ?>" />
    </div>
</div>