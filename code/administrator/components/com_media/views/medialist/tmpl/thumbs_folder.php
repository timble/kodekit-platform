<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="imgOutline">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a href="index.php?option=com_media&amp;view=mediaList&amp;tmpl=component&amp;folder=<?php echo $this->_tmp_folder->path_relative; ?>" target="folderframe">
				<img src="<?php echo JURI::root(true) ?>/media/com_media/images/folder.png" width="80" height="80" border="0" /></a>
		</div>
	</div>
	<div class="controls">
		<a class="delete-item" href="index.php?option=com_media&amp;task=folder.delete&amp;tmpl=component&amp;<?php echo JUtility::getToken(); ?>=1&amp;folder=<?php echo $this->state->folder; ?>&amp;rm[]=<?php echo $this->_tmp_folder->name; ?>" rel="<?php echo $this->_tmp_folder->name; ?>' :: <?php echo $this->_tmp_folder->files+$this->_tmp_folder->folders; ?>"><img src="<?php echo JURI::root(true) ?>/media/com_media/images/remove.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Delete' ); ?>" /></a>
		<input type="checkbox" name="rm[]" value="<?php echo $this->_tmp_folder->name; ?>" />
	</div>
	<div class="imginfoBorder ellipsis">
		<?php echo $this->_tmp_folder->name; ?>
	</div>
</div>