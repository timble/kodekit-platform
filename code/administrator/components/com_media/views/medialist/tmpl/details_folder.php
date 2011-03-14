<?php defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<td>
		<input type="checkbox" name="rm[]" value="<?php echo $this->_tmp_folder->name; ?>" />
	</td>
	<td class="description">
		<a href="index.php?option=com_media&amp;view=mediaList&amp;tmpl=component&amp;folder=<?php echo $this->_tmp_folder->path_relative; ?>" target="folderframe">
			<img src="<?php echo JURI::root(true) ?>/media/com_media/images/folder_sm.png" width="16" height="16" border="0" alt="<?php echo $this->_tmp_folder->name; ?>" />
			<?php echo $this->_tmp_folder->name; ?>
		</a>
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>