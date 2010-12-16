<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<?php if($wb_blockwraps == 1) : ?><!-- Breadcrumbs Block --><?php endif; ?>
<?php if($wb_blockwraps == 1) : ?><div id="path-wrap"><?php endif; ?>
	<?php if($wb_blockwraps == 0) : ?><!-- Breadcrumbs Block --><?php endif; ?>
	<div id="breadcrumbs" class="container_<?php echo $wb_grid_columns; ?> clearfix pos<?php echo $wb_breadcrumbs_position; ?>">
		<jdoc:include type="modules" name="breadcrumb" />
		<?php if($wb_show_date == 1) : ?><div class="date"><?php echo date('l, d F Y');?></div><?php endif; ?>
	</div>
<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>