<? /** $Id:  $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<?	if ( $section->name != '' ) : 
	$name = $section->name;
else :
	$name = @text('New Section');
endif; ?>
		
<script src="media://lib_koowa/js/koowa.js" />
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	if ( form.title.value == '' ){
		alert("<?=  @text('Section must have a title') ?>");
	} else {
		<? /*TODO what does this do? $editor->save( 'description' ) ;*/ ?>
		submitform(pressbutton);
	}
}
</script>

<form action="<?= @route('id='.$section->id) ?>" method="post" name="adminForm">
<input type="hidden" name="scope" value="<?= $section->id? $section->scope : $state->scope; ?>" />
<input type="hidden" name="id" value="<?= $section->id; ?>" />
<input type="hidden" name="oldtitle" value="<?= $section->title ; ?>" />
<div class="col width-60">
	<fieldset class="adminform">
		<legend><?= @text( 'Details' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" class="key">
					<?= @text( 'Scope' ); ?>:
				</td>
				<td colspan="2">
					<strong>
					<?= $section->id? $section->scope : $state->scope; ?>
					</strong>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="title">
						<?= @text( 'Title' ); ?>:
					</label>
				</td>
				<td colspan="2">
					<input class="text_area" type="text" name="title" id="title" value="<?= htmlspecialchars($section->title); ?>" size="50" maxlength="50" title="<?= @text( 'TIPTITLEFIELD' ); ?>" />
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="key">
					<label for="alias">
						<?= @text( 'Alias' ); ?>:
					</label>
				</td>
				<td colspan="2">
					<input class="text_area" type="text" name="slug" id="slug" value="<?= $section->slug; ?>" size="50" maxlength="255" title="<?= @text( 'ALIASTIP' ); ?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<?= @text( 'Published' ); ?>:
				</td>
				<td colspan="2">
					<?= @helper('admin::com.sections.template.helper.listbox.published', array('name' => 'enabled', 'state' => $section, 'deselect' => false)); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="ordering">
						<?= @text( 'Ordering' ); ?>:
					</label>
				</td>
				<td colspan="2">
					<?= @helper('admin::com.sections.template.helper.listbox.ordering'); ?>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top" class="key">
					<label for="access">
						<?= @text( 'Access Level' ); ?>:
					</label>
				</td>
				<td>
					<?= @helper('admin::com.sections.template.helper.listbox.access', array('name' => 'access', 'state' => $section, 'deselect' => false)); ?>
				</td>
				<td rowspan="4" width="50%">
					<? 
						if ($section->image != '') : 
							$path = JURI::root(true) . '/images/stories/'.$section->image;
						else : 
							$path = JURI::root(true) . '/media/system/images/blank.png';
						endif; 
					?>
					<img src="<?= $path;?>" name="imagelib" width="80" height="80" border="2" alt="<?= @text( 'Preview' ); ?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="image">
						<?= @text( 'Image' ); ?>:
					</label>
				</td>
				<td>
					<?= @helper('admin::com.sections.template.helper.listbox.image_names', array('name' => 'image')); ?>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="key">
					<label for="image_position">
					<?= @text( 'Image Position' ); ?>:
					</label>
				</td>
				<td>
					<?=  @helper('admin::com.sections.template.helper.listbox.image_position'); ?>
				</td>
			</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?= @text( 'Description' ); ?></legend>
			<table class="admintable">
			<tr>
				<td valign="top" colspan="3">
					<?= @editor( array('name' => 'description',
							'editor' => 'tinymce', 	
							'width' => '550', 
							'height' => '300', 
							'cols' => '60', 
							'rows' => '20', 
							'buttons' => null, 
							'options' => array('theme' => 'simple', 'pagebreak', 'readmore'))); 
					?>			
				</td>
			</tr>
			</table>
		</fieldset>
	</div>
</form>