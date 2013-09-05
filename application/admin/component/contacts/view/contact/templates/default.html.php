<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<!--
<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />
-->

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" id="contact-form" class="-koowa-form">
	<input type="hidden" name="id" value="<?= $contact->id; ?>" />
	<input type="hidden" name="access" value="0" />
	<input type="hidden" name="published" value="0" />
	
	<div class="main">
		<div class="title">
		    <input class="required" type="text" name="name" maxlength="255" value="<?= $contact->name ?>" placeholder="<?= translate('Name') ?>" />
		    <div class="slug">
		        <span class="add-on"><?= translate('Slug'); ?></span>
		        <input type="text" name="slug" maxlength="255" value="<?= $contact->slug ?>" />
		    </div>
		</div>

		<div class="scrollable">
			<fieldset>
				<legend><?= translate('Information'); ?></legend>
				<div>
				    <label for="position"><?= translate( 'Position' ); ?></label>
				    <div>
				        <input type="text" name="position" maxlength="255" value="<?= $contact->position; ?>" />
				    </div>
				</div>
				<div>
				    <label for="email_to"><?= translate( 'E-mail' ); ?></label>
				    <div>
				        <input type="text" name="email_to" maxlength="255" value="<?= $contact->email_to; ?>" />
				    </div>
				</div>
				<div>
				    <label for="address"><?= translate( 'Street Address' ); ?></label>
				    <div>
				        <textarea name="address" rows="5"><?= $contact->address; ?></textarea>
				    </div>
				</div>
				<div>
				    <label for="suburb"><?= translate( 'Town/Suburb' ); ?></label>
				    <div>
				        <input type="text" name="suburb" maxlength="100" value="<?= $contact->suburb;?>" />
				    </div>
				</div>
				<div>
				    <label for="state"><?= translate( 'State/County' ); ?></label>
				    <div>
				        <input type="text" name="state" maxlength="100" value="<?= $contact->state;?>" />
				    </div>
				</div>
				<div>
				    <label for="postcode"><?= translate( 'Postal Code/ZIP' ); ?></label>
				    <div>
				        <input type="text" name="postcode" maxlength="100" value="<?= $contact->postcode; ?>" />
				    </div>
				</div>
				<div>
				    <label for="country"><?= translate( 'Country' ); ?></label>
				    <div>
				        <input type="text" name="country" maxlength="100" value="<?= $contact->country;?>" />
				    </div>
				</div>
				<div>
				    <label for="telephone"><?= translate( 'Telephone' ); ?></label>
				    <div>
				        <input type="text" name="telephone" maxlength="255" value="<?= $contact->telephone; ?>" />
				    </div>
				</div>
				<div>
				    <label for="mobile"><?= translate( 'Mobile' ); ?></label>
				    <div>
				        <input type="text" name="mobile" maxlength="255" value="<?= $contact->mobile; ?>" />
				    </div>
				</div>
				<div>
				    <label for="fax"><?= translate( 'Fax' ); ?></label>
				    <div>
				        <input type="text" name="fax" maxlength="255" value="<?= $contact->fax; ?>" />
				    </div>
				</div>
			</fieldset>
		</div>
        <?= object('com:ckeditor.controller.editor')->render(array('name' => 'misc', 'text' => $contact->misc, 'toolbar' => 'basic')) ?>
	</div>

	<div class="sidebar">
        <?= import('default_sidebar.html'); ?>
	</div>
	
</form>

<script data-inline> $jQuery(".select-image").select2(); </script>