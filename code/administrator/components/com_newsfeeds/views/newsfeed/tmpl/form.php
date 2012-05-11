<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.tooltip') ?>
<?= @helper('behavior.validator') ?>

<?= @template('com://admin/default.view.form.toolbar'); ?>

<script src="media://lib_koowa/js/koowa.js" />

<script>
if(Form && Form.Validator) {
    Form.Validator.add('validate-count', {
        errorMsg: <?= json_encode(@text('Please enter a higher number than 0.')) ?>,
        test: function(field){
            return field.get('value').toInt() > 0;
        }
    });
}
</script>

<form action="" method="post" id="newsfeed-form" class="-koowa-form">
    <div class="form-body">
    	<div class="title">
    	    <input class="required" type="text" name="title" maxlength="255" value="<?= $newsfeed->title ?>" placeholder="<?= @text('Title') ?>" />
    	</div>
        
        <div class="form-content">
            <fieldset class="form-horizontal">
            <legend><?= @text( 'Details' ) ?></legend>
            <div class="control-group">
                <label class="control-label" for="link"><?= @text( 'Link' ) ?></label>
                <div class="controls">
                    <input class="required validate-url" type="text" name="link" value="<?= $newsfeed->link ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="numarticles"><?= @text( 'Number of Articles' ) ?></label>
                <div class="controls">
                    <input class="required validate-integer validate-count" type="text" name="numarticles" value="<?= $newsfeed->numarticles ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="cache_time"><?= @text( 'Cache time' ) ?></label>
                <div class="controls">
                    <input class="required validate-integer validate-count" type="text" name="cache_time" value="<?= $newsfeed->cache_time ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="rtl"><?= @text( 'RTL feed' ) ?></label>
                <div class="controls">
                    <?= @helper('select.booleanlist', array('name' => 'rtl', 'selected' => $newsfeed->rtl)) ?>
                </div>
            </div>
            </fieldset>
        </div>
    </div>
    <div class="sidebar">
        <fieldset class="form-horizontal">
            <legend><?= @text( 'Publish' ) ?></legend>
            <div class="control-group">
                <label class="control-label" for="enabled"><?= @text( 'Published' ) ?></label>
                <div class="controls">
                    <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $newsfeed->enabled)) ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="catid"><?= @text( 'Category' ) ?></label>
                <div class="controls">
                    <?= @helper('listbox.category', array('name' => 'catid', 'selected' => $newsfeed->catid, 'attribs' => array('id' => 'catid', 'class' => 'required'))) ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="slug"><?= @text( 'Slug' ); ?></label>
                <div class="controls">
                    <input type="text" name="slug" maxlength="255" value="<?= $newsfeed->slug; ?>" placeholder="<?= @text( 'Slug' ); ?>" />
                </div>
            </div>
    	</div>
    </div>
</form>