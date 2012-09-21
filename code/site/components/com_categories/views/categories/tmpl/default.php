<?
/**
 * @version		$Id: default.php 3537 2012-04-02 17:56:59Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<? foreach($categories as $category) : ?>
<article>
<div class="page-header">
    <h1>
        <a href="<?= @helper('route.category', array('row' => $category)) ?>">
        	<?= @escape($category->title);?>
        </a>
    </h1>
</div>

<div class="clearfix">
	<? if ($category->description) : ?>
	<p><?= $category->description; ?></p>
	<? endif; ?>
	
	<? if (isset($category->image)) : ?>
		<img class="thumbnail" align="right" src="<?= $category->image->path ?>" height="<?= $category->image->height ?>" width="<?= $category->image->width ?>" />
	<? endif; ?>
</div>

<a href="<?= @helper('route.category', array('row' => $category)) ?>"><?= @text('Read more') ?></a>
</article>
<? endforeach; ?>
