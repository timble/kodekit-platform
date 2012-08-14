<div class="control-group">
    <label class="control-label" for="parent"><?= @text('Page') ?></label>
    <div id="parent" class="controls">
        <?= @helper('listbox.pages', array('name' => 'link_id', 'selected' => $page->link_id)) ?>
    </div>
</div>
