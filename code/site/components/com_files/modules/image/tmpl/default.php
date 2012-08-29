<?
/**
 * @version		$Id: form.php 1294 2011-05-16 22:57:57Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<div align="center">
    <? if ($link) : ?>
    <a href="<?= $link; ?>" target="_self">
    <? endif; ?>
	    <?= JHTML::_('image', $image->folder.'/'.$image->name, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
    <? if ($link) : ?>
    </a>
    <? endif; ?>
</div>