<?
/**
 * @version     $Id: form.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.keepalive') ?>
<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />

<?= @template('com://admin/default.view.form.toolbar') ?>

<form action="" method="post" id="language-form" class="-koowa-form">
    <div class="form-content">
        <div class="grid_8">
    		<fieldset class="form-horizontal">
    			<legend><?= @text('Details') ?></legend>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('Name') ?></label>
    			    <div class="controls">
    			        <input id="name_field" type="text" name="name" value="<?= $language->name ?>" />
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('Native Name') ?></label>
    			    <div class="controls">
    			        <input id="native_field" type="text" name="native_name" value="<?= $language->native_name ?>" />
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('Slug') ?></label>
    			    <div class="controls">
    			        <input id="alias_field" type="text" name="slug" value="<?= $language->slug ?>" />
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('ISO Code') ?></label>
    			    <div class="controls">
    			        <input type="text" name="iso_code" type="text" value="<?= $language->iso_code ?>" />
    			    </div>
    			</div>
    		</fieldset>
    	</div>
	</div>
</form>