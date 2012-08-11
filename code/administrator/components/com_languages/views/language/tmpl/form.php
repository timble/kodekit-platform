<?= @helper('behavior.keepalive') ?>
<?= @helper('behavior.validator') ?>
<?= @helper('behavior.modal')?>

<script src="media://lib_koowa/js/koowa.js" />
<script src="media://com_languages/js/language-form.js" />

<?= @template('com://admin/default.view.form.toolbar') ?>

<form action="" method="post" id="language-form" class="-koowa-form adminform">
    <div class="form-content">
        <div class="grid_8">
            <? if(!$language->id) : ?>
        		<fieldset id="lang_langpack" class="form-horizontal">
        			<legend><?= @text('Language pack') ?></legend>
        			<div class="control-group">
        			    <label><?= @text('Language pack') ?></label>
        			    <div class="controls">
        			        <?= @helper('listbox.langpacks', array('filter' => array('translatable' => false), 'attribs' => array('id' => 'langpack'))) ?>
        			    </div>
        			</div>
        		</fieldset>
        		<fieldset id="lang_details">
        			<legend><?= @text('Details') ?></legend>
        			<table class="admintable">
        				<tr style="height:28px">
        					<td class="key">
        						<label><?= @text('ISO Code') ?></label>
        					</td>
        					<td>
        					    <span>
        					        <input type="text" id="iso_code_lang_field" name="iso_code_lang" maxlength="2" size="1" type="text" class="languages-iso-code" /> - 
        					        <input type="text" id="iso_code_country_field" name="iso_code_country" maxlength="2" size="1" type="text" class="languages-iso-code" />
        					    </span>
        					</td>
        				</tr>
        				<tr>
        					<td class="key">
        						<label><?= @text('Name') ?></label>
        					</td>
        					<td>
        					    <input id="name_field" type="text" name="name" value="<?= $language->name ?>" />
        					</td>
        				</tr>
        				<tr>
        					<td class="key">
        						<label><?= @text('Native Name') ?></label>
        					</td>
        					<td>
        					    <input id="native_field" type="text" name="native_name" value="<?= $language->native_name ?>" />
        					</td>
        				</tr>
        				<tr>
        					<td class="key">
        						<label><?= @text('Slug') ?></label>
        					</td>
        					<td>
        						<input id="alias_field" type="text" name="slug" value="<?= $language->slug ?>" />
        					</td>
        				</tr>
        				<tr>
        					<td class="key">
        						<label><?= @text('Flag image') ?></label>
        					</td>
        					<td>
        						<?//= @helper('image.flag') ?>
        						<a rel="{handler: 'iframe', size: {x: 585, y: 640}}" href="<?=  @route('view=countries&limit=49&tmpl=component') ?>" class="modal">
        						    <?=@text('Pick a flag...') ?>
        						</a>
        					</td>
        				</tr>
        			</table>
        		</fieldset>
        	<? endif ?>
    	</div>
	</div>
</form>