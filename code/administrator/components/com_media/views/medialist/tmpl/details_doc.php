<?php defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<td>
		<input type="checkbox" name="rm[]" value="<?php echo $this->_tmp_doc->name; ?>" />
	</td>
	<td class="description">
		<a>
			<img src="<?php echo $this->_tmp_doc->icon_16; ?>" width="16" height="16" border="0" alt="<?php echo $this->_tmp_doc->name; ?>" />
		</a>
		<?php echo $this->_tmp_doc->name; ?>
	</td>
	<td>&nbsp;</td>
	<td align="center">
		<?php echo MediaHelper::parseSize($this->_tmp_doc->size); ?>
	</td>
</tr>