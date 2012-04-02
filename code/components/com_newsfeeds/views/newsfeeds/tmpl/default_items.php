<?
/**
 * @version		$Id: default.php 3314 2012-02-10 02:14:52Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<form action="" method="get" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<? if ( $params->get( 'show_headings' ) ) : ?>
<tr>
	<td class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>" align="right" width="5">
		<?= @text('Num'); ?>
	</td>
	<? if ( $params->get( 'show_name' ) ) : ?>
	<td height="20" width="90%" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
		<?= @text( 'Feed Name' ); ?>
	</td>
	<? endif; ?>
	<? if ( $params->get( 'show_articles' ) ) : ?>
	<td height="20" width="10%" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>" align="center" nowrap="nowrap">
		<?= @text( 'Num Articles' ); ?>
	</td>
	<? endif; ?>
 </tr>
<? endif; ?>

<? $i = 1; ?>
<? foreach ($newsfeeds as $newsfeed) : ?>
<tr class="sectiontableentry<?= ($i&1) ? '1' : '2'; ?>">
	<td align="right">
		<?= $i++; ?>
	</td>
	<td height="20" width="90%">
		<a href="<?= @route('view=newsfeed&category='.$category->id.':'.$category->slug.'&id='. $newsfeed->id.':'.$newsfeed->slug); ?>" class="category<?= @escape($params->get('pageclass_sfx')); ?>">
			<?= @escape($newsfeed->title); ?></a>
	</td>
	<?php if ( $params->get( 'show_articles' ) ) : ?>
	<td height="20" width="10%" align="center">
		<?= $newsfeed->numarticles; ?>
	</td>
	<? endif; ?>
</tr>
<? endforeach; ?>
<tr>
	<td align="center" colspan="4" class="sectiontablefooter<?= @escape($params->get('pageclass_sfx')); ?>">
	    <?= @helper('paginator.pagination', array('total' => $total)) ?>
	</td>
</tr>
</table>
</form>
