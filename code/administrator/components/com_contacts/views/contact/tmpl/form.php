<?php
/**
 * @version     $Id: form.php 2004 2011-06-26 16:32:54Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?= @helper('behavior.tooltip') ?>
<?= @helper('behavior.validator') ?>
  
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route('id='.$contact->id);?>" method="post" id="contact-form" class="-koowa-form">
	<input type="hidden" name="id" value="<?= $contact->id; ?>" />

	<div style="width:60%; float: left" id="mainform">
		<fieldset class="adminform">
			<legend><?= @text('Details'); ?></legend>
			
			<table class="admintable">
				<tr>
					<td class="key">
						<label for="name">
							<?= @text( 'Name' ); ?>:
						</label>
					</td>
					<td >
						<input class="inputbox" type="text" name="name" id="name" size="60" maxlength="255" value="<?= $contact->name; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="alias">
							<?= @text( 'Alias' ); ?>:
						</label>
					</td>
					<td >
						<input class="inputbox" type="text" name="alias" id="alias" size="60" maxlength="255" value="<?= $contact->slug; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<?= @text( 'Published' ); ?>:
					</td>
					<td>
						<?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $contact->enabled)); ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="category">
							<?= @text( 'Category' ); ?>:
						</label>
					</td>
					<td>
						<?= @helper('com://admin/categories.template.helper.listbox.categories', array(
                            'name' => 'category',
                            'text' => 'title',
                            'filter' => array('section' => 'com_contact_details'),
                            'selected' => $contact->category,
                        )); ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="user_id">
							<?= @text( 'Linked to User' ); ?>:
						</label>
					</td>
					<td>
					    <?= @helper('behavior.autocomplete', array(
                           'value' => $contact->user_id, 
                           'name'   => 'user',
                           'target' => 'name',
                           'model'  => @route('option=com_users&view=users&format=json', true),
                           'label'  => 'User',
                           'text'   => KFactory::get('com://admin/users.model.users')->id($contact->user_id)->getItem()->name,
                           'placeholder' => 'Start typing a name'
                       )) ?>
					</td>
				</tr>
				<tr>
					<td valign="top" class="key">
						<label for="ordering">
							<?= @text( 'Ordering' ); ?>:
						</label>
					</td>
					<td>
						<?= @helper('listbox.ordering'); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" class="key">
						<label for="access">
							<?= @text( 'Access' ); ?>:
						</label>
					</td>
					<td>
						<?= @helper('com://admin/default.template.helper.listbox.access', array('name' => 'access', 'selected' => $contact->access, 'deselect' => false)); ?>
					</td>
				</tr>
				<?php if ($contact->id) : ?>
				<tr>
					<td class="key">
						<label>
							<?= @text( 'ID' ); ?>:
						</label>
					</td>
					<td>
						<strong><?= $contact->id;?></strong>
					</td>
				</tr>
				<?php endif; ?>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?= @text('Information'); ?></legend>
			
			<table class="admintable">
				<tr>
					<td class="key">
					<label for="con_position">
						<?= @text( 'Contact\'s Position' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="con_position" id="con_position" size="60" maxlength="255" value="<?= $contact->con_position; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="email_to">
							<?= @text( 'E-mail' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="email_to" id="email_to" size="60" maxlength="255" value="<?= $contact->email_to; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
						<label for="address">
							<?= @text( 'Street Address' ); ?>:
							</label>
						</td>
						<td>
 							<textarea name="address" id="address" rows="3" cols="45" class="inputbox"><?= $contact->address; ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="suburb">
							<?= @text( 'Town/Suburb' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="suburb" id="suburb" size="60" maxlength="100" value="<?= $contact->suburb;?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="state">
							<?= @text( 'State/County' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="state" id="state" size="60" maxlength="100" value="<?= $contact->state;?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="postcode">
							<?= @text( 'Postal Code/ZIP' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="postcode" id="postcode" size="60" maxlength="100" value="<?= $contact->postcode; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="country">
							<?= @text( 'Country' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="country" id="country" size="60" maxlength="100" value="<?= $contact->country;?>" />
					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
					<label for="telephone">
					<?= @text( 'Telephone' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="telephone" id="telephone" size="60" maxlength="255" value="<?= $contact->telephone; ?>" />
  					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
						<label for="mobile">
							<?= @text( 'Mobile' ); ?>:
						</label>
					</td>
					<td>
 						<input class="inputbox" type="text" name="mobile" id="mobile" size="60" maxlength="255" value="<?= $contact->mobile; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
						<label for="fax">
							<?= @text( 'Fax' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="fax" id="fax" size="60" maxlength="255" value="<?= $contact->fax; ?>" />
 					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="webpage">
							<?= @text( 'Webpage' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="webpage" id="webpage" size="60" maxlength="255" value="<?= $contact->webpage; ?>" />
					</td>
				</tr>
				<tr>
					<td  class="key" valign="top">
						<label for="misc">
							<?= @text( 'Miscellaneous Info' ); ?>:
						</label>
					</td>
					<td>
						<textarea name="misc" id="misc" rows="5" cols="45" class="inputbox"><?= $contact->misc; ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="image">
							<?= @text( 'Image' ); ?>:
						</label>
					</td>
					<td >
						<?= @helper('image.listbox', array('name' => 'image')); ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	<div class="col width-40">
		<fieldset class="adminform">
			<legend><?= @text('Parameters'); ?></legend>

			<?= @helper('tabs.startPane'); ?>
			<?= @helper('tabs.startPanel', array('title' => @text('Contact Parameters'))); ?>
			<?= $contact->params->render(); ?>
			<?= @helper('tabs.endPanel', array()); ?>
			<?= @helper('tabs.startPanel', array('title' => @text('Advanced Parameters'))); ?>
			<?= $contact->params->render('params', 'advanced'); ?>
			<?= @helper('tabs.endPanel'); ?>
			<?= @helper('tabs.startPanel', array('title' => @text('E-mail Parameters'))); ?>
			<?= $contact->params->render('params', 'email'); ?>
			<?= @helper('tabs.endPanel'); ?>
			<?= @helper('tabs.endPane'); ?>

		</fieldset>
	</div>
	
</form>