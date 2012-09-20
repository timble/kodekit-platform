<?
/**
 * @version		$Id: default.php 3647 2012-05-01 12:56:08Z tomjanssens $
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<div class="page-header">
    <h1><?= @escape($params->get('page_title')); ?></h1>
</div>

<? if ($params->get('show_description_image') && $section->image): ?>
<? if (isset($section->image)) : ?>
    <img src="<?= $section->image->path ?>" height="<?= $section->image->height ?>" width="<?= $section->image->width ?>" />
    <? endif; ?>
<? endif; ?>

<? if ($params->get('show_description') && $section->description): ?>
    <?= $section->description; ?>
<? endif; ?>

<? foreach($categories as $category) : ?>
	<h2>
		<a href="<?= @helper('route.category', array('row' => $category)) ?>" class="category">
			<?= @escape($category->title);?>
		</a>
	</h2>
	<p>
	    <?= $category->description;?>
	</p>
<? endforeach; ?>


