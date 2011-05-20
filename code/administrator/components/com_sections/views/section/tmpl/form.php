<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.keepalive'); ?>

<?	if ( $section->name != '' ) : 
	$name = $section->name;
else :
	$name = @text('New Section');
endif; ?>
		
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<script>
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	if ( form.title.value == '' ){
		alert("<?=  @text('Section must have a title') ?>");
	} else {
		submitform(pressbutton);
	}
}
</script>

<form action="<?= @route('id='.$section->id) ?>" method="post" name="adminForm">
	<input type="hidden" name="scope" value="<?= $section->id? $section->scope : $state->scope; ?>" />
	<input type="hidden" name="oldtitle" value="<?= $section->title ; ?>" />
	
	<div class="grid_8">
		<div class="border-radius-4 title clearfix">
			<input class="inputbox border-radius-4" type="text" name="title" id="title" size="40" maxlength="255" value="<?= $section->title; ?>" placeholder="<?= @text( 'Title' ); ?>" />
		
			<label for="alias">
				<?= @text( 'Alias' ); ?>
				<input class="inputbox border-radius-4" type="text" name="alias" id="alias" size="40" maxlength="255" value="<?= $section->slug; ?>" title="<?= @text( 'ALIASTIP' ); ?>" placeholder="<?= @text( 'Alias' ); ?>" />
			</label>
		</div>
		<?= @editor( array('name' => 'description',
				'editor' => 'tinymce', 	
				'width' => '100%', 
				'height' => '300', 
				'cols' => '60', 
				'rows' => '20', 
				'buttons' => null, 
				'options' => array('theme' => 'simple', 'pagebreak', 'readmore'))); 
		?>
	</div>
	<div class="grid_4">
		<div class="panel">
			<h3><?= @text( 'Publish' ); ?></h3>
			<table class="paramlist admintable">
				<tr>
					<td width="100" class="key">
						<?= @text( 'Scope' ); ?>:
					</td>
					<td>
						<strong><?= $section->id ? $section->scope : $state->scope; ?></strong>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?= @text( 'Published' ); ?>:
					</td>
					<td>
						<?= @helper('listbox.published', array('name' => 'enabled', 'state' => $section, 'deselect' => false)); ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="ordering">
							<?= @text( 'Ordering' ); ?>:
						</label>
					</td>
					<td>
						<?= $section->id ? @helper('admin::com.categories.template.helper.listbox.order', array('package'=>'sections')) : ''; ?>
					</td>
				</tr>
				<tr>
					<td nowrap="nowrap" class="key">
						<label for="access">
							<?= @text( 'Access Level' ); ?>:
						</label>
					</td>
					<td>
						<?= @helper('listbox.access', array('name' => 'access', 'state' => $section, 'deselect' => false)); ?>
					</td>
				</tr>
			</table>
		</div>
		<div class="panel">
			<h3><?= @text( 'Image' ); ?></h3>
			<table class="paramlist admintable">
				<tr>
					<td class="key">
						<label for="image">
							<?= @text( 'Image' ); ?>:
						</label>
					</td>
					<td>
						<?= @helper('image.listbox', array('name' => 'image')); ?>
					</td>
				</tr>
				<tr>
					<td nowrap="nowrap" class="key">
						<label for="image_position">
							<?= @text( 'Image Position' ); ?>:
						</label>
					</td>
					<td>
						<?=  @helper('image.position'); ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>