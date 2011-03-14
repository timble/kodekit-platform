<?php defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<td>
		<input type="checkbox" name="rm[]" value="<?php echo $this->_tmp_img->name; ?>" />
	</td>
	<td class="description">
		<a href="<?php echo  COM_MEDIA_BASEURL.'/'.$this->_tmp_img->path_relative; ?>" title="<?php echo $this->_tmp_img->name; ?>" class="img-preview">
			<img src="<?php echo COM_MEDIA_BASEURL.'/'.$this->_tmp_img->path_relative; ?>" width="<?php echo $this->_tmp_img->width_16; ?>" height="<?php echo $this->_tmp_img->height_16; ?>" alt="<?php echo $this->_tmp_img->name; ?> - <?php echo MediaHelper::parseSize($this->_tmp_img->size); ?>" border="0" />
			<?php echo $this->escape( $this->_tmp_img->name); ?>
		</a>
	</td>
	<td align="center">
		<?php echo $this->_tmp_img->width; ?> x <?php echo $this->_tmp_img->height; ?>
	</td>
	<td align="center">
		<?php echo MediaHelper::parseSize($this->_tmp_img->size); ?>
	</td>
</tr>