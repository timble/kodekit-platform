<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2009 - 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<form action="<?= @route(); ?>" method="get" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<? if($params->def('show_headings', 1)) : ?>
<tr>
	<td width="10" style="text-align:right;" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
		<?= @text('Num'); ?>
	</td>
	<td width="90%" height="20" class="sectiontableheader<?= @escape($params->get( 'pageclass_sfx' )); ?>">
		<?= @helper('grid.sort', array('column' => 'title')) ?>
	</td>
</tr>
<? endif; ?>

<? $i = 1; ?>
<? foreach ($weblinks as $weblink) : ?>
<tr class="sectiontableentry<?= ($i&1) ? '1' : '2'; ?>">
	<td align="right">
		<?= $i; ?>
	</td>
	<td height="20">
		<? if ( $image ) : ?>
			&nbsp;&nbsp;<img src="<?= $image['src'] ?>" title="<?= $image['title'] ?>" />&nbsp;&nbsp;
		<? endif; ?>
		<?
			$link  = @route('view=weblink&&id='. $weblink->id);
			$class = 'category'.@escape($params->get( 'pageclass_sfx' ));
		?>
		<a href="<?= $link ?>" class="<?= $class ?>"><?= @escape($weblink->title) ?></a>
			
		<? if ( $params->get( 'show_link_description' ) ) : ?>
			<br /><span class="description"><?= nl2br(@escape($weblink->description)); ?></span>
		<? endif; ?>
	</td>
</tr>
<? $i++; ?>
<? endforeach; ?>
<tr>
	<td align="center" colspan="4" class="sectiontablefooter<?= @escape($params->get('pageclass_sfx')); ?>">
	    <?= @helper('paginator.pagination', array('total' => $total)) ?>
	</td>
</tr>
</table>
</form>
