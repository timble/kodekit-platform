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

<?php if($this->countModules('user3 || user4')) : ?>
	<?php if($wb_blockwraps == 1) : ?><div id="navigation-wrap"><?php endif; ?>
		<div id="navigation" class="container_<?php echo $wb_grid_columns; ?> clearfix list-reset float-list">
			<jdoc:include type="modules" name="user3" style="none" />
			<jdoc:include type="modules" name="user4" style="none" />
		</div>
	<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>
<?php endif; ?>